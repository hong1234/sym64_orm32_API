<?php
namespace App\Controller;

use App\Service\BookService;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

#[Route('/api')]
class ReviewController extends BaseController {

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

}

