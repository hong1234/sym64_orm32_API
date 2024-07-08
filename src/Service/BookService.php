<?php

namespace App\Service;

use App\Repository\BookRepository;
use App\Repository\ReviewRepository;

use App\Dto\BookDto;
use App\Dto\ReviewDto;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Serializer;

class BookService {

    private $bookRepository;
    private $reviewRepository;

    public function __construct(BookRepository $bookRepository, ReviewRepository $reviewRepository) {
        $this->bookRepository = $bookRepository;
        $this->reviewRepository = $reviewRepository;
    }

    public function addBook($data) {
        $book = $this->bookRepository->saveBook($data);
        return $this->toJson($book);

        // $bookDto = $this->getBookDto($book);
        // return $this->toJson($bookDto);
    }

    public function updateBook($bookId, $data) {    
        $book = $this->bookRepository->find($bookId);
        if (!$book)
            return null;

        $book = $this->bookRepository->updateBook($book, $data);
        return $this->toJson($book);
        // $bookDto = $this->getBookDto($book);
        // return $this->toJson($bookDto);
    }

    public function deleteBook($bookId) {   
        $book = $this->bookRepository->find($bookId);
        if (!$book)
            return null;

        $this->bookRepository->removeBook($book);
        return 1;
    }

    public function getBook(int $bookId){  
        $book = $this->bookRepository->find($bookId);
        if (!$book)
            return null;
        return $this->toJson($book);
        // $bookDto = $this->getBookDto($book);
        // return $this->toJson($bookDto);
    }

    public function searchBookByTitle($searchkey){  
	    $books = $this->bookRepository->searchBook($searchkey);
        // return $this->toJson($books);

        $bookDtos = [];
        foreach ($books as $book) {
            $bookDtos[] = $this->getBookDto($book);
        }
        return $this->toJson($bookDtos);
    }

    public function allBooks(){
    	$books = $this->bookRepository->findAll();
        // return $this->toJson($books);
        $bookDtos = [];
        foreach ($books as $book) {
            $bookDtos[] = $this->getBookDto($book);
        }
        return $this->toJson($bookDtos);
    }

    // reviews ---

    public function addReview($bookId, $data){ 
        // $book = $this->bookRepository->findOneBy(['id' => $bookId]);
        $book = $this->bookRepository->find($bookId);
        if (!$book) 
            return null;

        $review = $this->reviewRepository->saveReview($book, $data);
        return $this->toJson($review);
        // $reviewDto = $this->getReviewDto($review);
        // return $this->toJson($reviewDto);
    }

    public function deleteReview($reviewId){
        // $review = $this->reviewRepository->findOneBy(['id' => $reviewId]);
        $review = $this->reviewRepository->find($reviewId);
        if (!$review)
            return null;
            
        $this->reviewRepository->removeReview($review);
        return 1;
    }

    public function bookReviews($bookId){ 
        $book = $this->bookRepository->find($bookId);
        if (!$book)
            return null; 
        // return $this->toJson($book->getReviews());
        $reviews = $book->getReviews();
        
        $reviewDtos = [];
        foreach ($reviews as $review) {
            $reviewDtos[] = $this->getReviewDto($review);
        }
        return $this->toJson($reviewDtos);
    }

    public function toJson($items){
        $serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);
        return $serializer->serialize($items, 'json', [
		    'circular_reference_handler' => function ($object) { return $object->getId(); },
            'ignored_attributes' => ['book']
	    ]);
    }

    public function getBookDto($book){
        $bookDto = new BookDto();
        $bookDto->setId($book->getId())
            ->setTitle($book->getTitle())
            ->setContent($book->getContent())
            ->setCreatedOn(date_format($book->getCreatedOn(),'d-m-Y H:i'));

        if($book->getUpdatedOn() !=null)
            $bookDto->setUpdatedOn(date_format($book->getUpdatedOn(),'d-m-Y H:i'));

        return $bookDto;
    }

    public function getReviewDto($view){
        $viewDto = new ReviewDto();
        $viewDto->setId($view->getId())
            ->setName($view->getName())
            ->setEmail($view->getEmail())
            ->setContent($view->getContent())
            ->setCreatedOn(date_format($view->getCreatedOn(), 'd-m-Y H:i'));

        if($view->getUpdatedOn() !=null)
            $viewDto->setUpdatedOn(date_format($view->getUpdatedOn(), 'd-m-Y H:i'));

        return $viewDto;
    }

}