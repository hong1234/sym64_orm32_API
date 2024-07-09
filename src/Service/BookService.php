<?php

namespace App\Service;

use App\Repository\BookRepository;
use App\Repository\ReviewRepository;

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

}