<?php

namespace App\Service;

use App\Repository\BookRepository;
use App\Repository\ReviewRepository;

use App\Dto\BookDto;
use App\Dto\ReviewDto;

class BookService {

    private $bookRepository;
    private $reviewRepository;

    public function __construct(BookRepository $bookRepository, ReviewRepository $reviewRepository) {
        $this->bookRepository = $bookRepository;
        $this->reviewRepository = $reviewRepository;
    }

    public function addBook($data) {
        $book = $this->bookRepository->saveBook($data);
        return $book;
    }

    public function updateBook($bookId, $data) {    
        $book = $this->getBook($bookId);
        if (!$book)
            return null;
            
        $book = $this->bookRepository->updateBook($book, $data);
        return $book;
    }

    public function deleteBook($bookId) {   
        $book = $this->getBook($bookId);
        if (!$book) 
            return null;
            // throw new \NotFoundException("Book {$bookId} not found ");
        
        $this->bookRepository->removeBook($book);
        return 1;
    }

    public function getBook(int $bookId) {  
        return $this->bookRepository->find($bookId);
    }

    public function searchBookByTitle($searchkey) {  
	    $books = $this->bookRepository->searchBook($searchkey);
        return $books;
    }

    public function allBooks() {
    	$books = $this->bookRepository->findAll();
        return $books;
    }

    // reviews ---

    public function addReview($bookId, $data) { 
        $book = $this->getBook($bookId);
        
        if(!$book) 
            return null;

        $review = $this->reviewRepository->saveReview($book, $data);
        return $review;
    }

    public function deleteReview($reviewId) {
        $review = $this->reviewRepository->find($reviewId);

        if (!$review)
            return null;
            
        $this->reviewRepository->removeReview($review);
        return 1;
    }

    public function bookReviews($bookId) { 
        $book = $this->getBook($bookId);
        
        if (!$book)
            return null; 
        
        $reviews = $book->getReviews();
        return $reviews;
    }

    // dto transform ---

    public function getBookDtoArray($books){
        $bookDtos = [];
        foreach ($books as $book) {
            $bookDtos[] = $this->getBookDto($book);
        }
        return $bookDtos;
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

    public function getBookDtoDeep($book){
        $reviewDtos = $this->getReviewDtoArray($book->getReviews());

        $bookDto = new BookDto();
        $bookDto->setId($book->getId())
            ->setTitle($book->getTitle())
            ->setContent($book->getContent())
            ->setCreatedOn(date_format($book->getCreatedOn(),'d-m-Y H:i'))
            ->setReviews($reviewDtos);

        if($book->getUpdatedOn() !=null)
            $bookDto->setUpdatedOn(date_format($book->getUpdatedOn(),'d-m-Y H:i'));

        return $bookDto;
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

}