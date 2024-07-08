<?php
namespace App\Controller;

use App\Service\BookService;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api')]
class ReviewController extends AbstractController
{
    private $bookService;
   
    public function __construct(BookService $bookService) {
        $this->bookService = $bookService;
    }

    #[Route('/reviews/{bookId}', name: 'review_add', methods: ['POST'], requirements: ['bookId' => '\d+'])]
    public function addReviewToBook($bookId, Request $request): Response { 
        $data = json_decode($request->getContent(), true);
        $reviewJson = $this->bookService->addReview($bookId, $data);
        if($reviewJson==null)
            return new Response(json_encode(['error' => 'Book not found']), Response::HTTP_NOT_FOUND);
        return new Response($reviewJson, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    #[Route('/reviews/{reviewId}', name: 'review_delete', requirements: ['reviewId' => '\d+'], methods: ['DELETE'])]
    public function deleteReview($reviewId): Response {
        $status = $this->bookService->deleteReview($reviewId);
        if ($status ==null)
            return new Response(json_encode(['error' => 'Review not found']), Response::HTTP_NOT_FOUND);
        return new Response(json_encode(['status' => 'Review Id='.$reviewId.' deleted']), Response::HTTP_OK);
    }

    #[Route('/reviews/{bookId}', name: 'review_of_book', methods: ['GET'], requirements: ['bookId' => '\d+'])]
    public function reviewsOfBook(int $bookId): Response { 
        $bookReviewsJson = $this->bookService->bookReviews($bookId);
        if ($bookReviewsJson==null) 
            return new Response(json_encode(['error' => 'Book not found']), Response::HTTP_NOT_FOUND);
        return new Response($bookReviewsJson, 200, ['Content-Type' => 'application/json']);  
    }

}

