<?php
namespace App\Controller;

use App\Service\BookService;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Serializer;

use App\Dto\ReviewDto;

#[Route('/api')]
class ReviewController extends AbstractController {

    private $bookService;
   
    public function __construct(BookService $bookService) {
        $this->bookService = $bookService;
    }

    #[Route('/reviews/{bookId}', name: 'review_add', requirements: ['bookId' => '\d+'], methods: ['POST'])]
    public function addReviewToBook(int $bookId, Request $request): Response { 
        $rs = [];
        $data = $this->getInputArray($request);
        $review = $this->bookService->addReview($bookId, $data);
        
        if(!$review){
            $rs = [
                "code"    => "404",
                "message" => "Book {$bookId} not found "
            ];
            return new Response(json_encode($rs), Response::HTTP_NOT_FOUND, ['Content-Type' => 'application/json']);
        }

        $rs = [
            "code"    => "200",
            "message" => "1 Review created",
            "data"    => $this->getReviewDto($review)
        ];
        return new Response($this->toJson($rs), Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    #[Route('/reviews/{reviewId}', name: 'review_delete', requirements: ['reviewId' => '\d+'], methods: ['DELETE'])]
    public function deleteReview(int $reviewId): Response {
        $rs = [];
        $status = $this->bookService->deleteReview($reviewId);

        if (!$status){
            $rs = [
                "code"    => "404",
                "message" => "Review {$reviewId} not found "
            ];
            return new Response(json_encode($rs), Response::HTTP_NOT_FOUND, ['Content-Type' => 'application/json']);
        }
            
        $rs = [
            "code"    => "200",
            "message" => "Review {$reviewId} deleted",
        ];
        return new Response($this->toJson($rs), Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    #[Route('/reviews/{bookId}', name: 'review_of_book', requirements: ['bookId' => '\d+'], methods: ['GET'])]
    public function reviewsOfBook(int $bookId): Response { 
        $rs = [];
        $reviews = $this->bookService->bookReviews($bookId);

        if (!$reviews) {
            $rs = [
                "code"    => "404",
                "message" => "Book {$bookId} not found "
            ];
            return new Response($this->toJson($rs), Response::HTTP_NOT_FOUND, ['Content-Type' => 'application/json']);
        }

        $rs = [
            "code"    => "200",
            "message" => "all reviews of book {$bookId}",
            "data"    => $this->getReviewDtoArray($reviews)
            // "data"    =>  $reviews
        ];
        return new Response($this->toJson($rs), Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    public function getInputArray(Request $request){
        return json_decode($request->getContent(), true);
    }

    public function getReviewDtoArray($reviews){
        $reviewDtos = [];
        foreach ($reviews as $review) {
            $reviewDtos[] = $this->getReviewDto($review);
        }
        return $reviewDtos;
    }

    public function getReviewDto($review){
        $reviewDto = new ReviewDto();
        $reviewDto->setId($review->getId())
            ->setName($review->getName())
            ->setEmail($review->getEmail())
            ->setContent($review->getContent())
            ->setCreatedOn(date_format($review->getCreatedOn(), 'd-m-Y H:i'))
            ->setBookid($review->getBook()->getId())
            ;

        if($review->getUpdatedOn() !=null)
            $viewDto->setUpdatedOn(date_format($review->getUpdatedOn(), 'd-m-Y H:i'));

        return $reviewDto;
    }

    public function toJson($items){
        $serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);
        return $serializer->serialize($items, 'json', [
		    'circular_reference_handler' => function ($object) { return $object->getId(); },
            'ignored_attributes' => ['book']
	    ]);
    }

}

