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

use App\Dto\BookDto;

#[Route('/api')]
class BookController extends AbstractController {

    private $bookService;
   
    public function __construct(BookService $bookService) {
        $this->bookService = $bookService;
    }

    #[Route('/books', name: 'book_add', methods: ['POST'])]
    public function addBook(Request $request): Response {
        $data = $this->getInputArray($request);

        $book = $this->bookService->addBook($data);

        $rs = [
            "code"    => "200",
            "message" => "1 Book created",
            "data"    => $this->getBookDto($book)
            // "data"    => $book
        ];
        return new Response($this->toJson($rs), Response::HTTP_CREATED, ['Content-Type' => 'application/json']);
    }

    #[Route('/books/{bookId}', name: 'book_update', requirements: ['bookId' => '\d+'], methods: ['PUT'])]
    public function updateBook(int $bookId, Request $request): Response { 
        $rs = [];
        $data = $this->getInputArray($request);
        $book = $this->bookService->updateBook($bookId, $data);

        if(!$book){
            $rs = [
                "code"    => "404",
                "message" => "Book {$bookId} not found "
            ];
            return new Response($this->toJson($rs), Response::HTTP_NOT_FOUND, ['Content-Type' => 'application/json']);
        } 

        $rs = [
            "code"    => "200",
            "message" => "Book {$bookId} updated",
            "data"    => $this->getBookDto($book)
            // "data"    => $book
        ];
        return new Response($this->toJson($rs), Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    #[Route('/books/{bookId}', name: 'book_delete', requirements: ['bookId' => '\d+'], methods: ['DELETE'])]
    public function deleteBook(int $bookId): Response { 
        $rs = [];  
        $status = $this->bookService->deleteBook($bookId);
        
        if(!$status) {
            // throw $this->createNotFoundException('No book found for id '.$bookId);
            $rs = [
                "code"    => "404",
                "message" => "Book {$bookId} not found "
            ];
            return new Response($this->toJson($rs), Response::HTTP_NOT_FOUND, ['Content-Type' => 'application/json']);
        }

        $rs = [
            "code"    => "200",
            "message" => "Book {$bookId} deleted",
        ];
        return new Response($this->toJson($rs), Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    #[Route('/books/{bookId}', name: 'book_show', requirements: ['bookId' => '\d+'], methods: ['GET'])]
    public function showBook(int $bookId): Response {  
        $rs = [];
        $book = $this->bookService->getBook($bookId);
        
        if(!$book) {
            $rs = [
                "code"    => "404",
                "message" => "Book {$bookId} not found "
            ];
            return new Response(json_encode($rs), Response::HTTP_NOT_FOUND, ['Content-Type' => 'application/json']);
        }

        $rs = [
            "code"    => "200",
            "message" => "Book {$bookId}",
            "data"    => $this->getBookDto($book)
            // "data"    =>  $book
        ];
        return new Response($this->toJson($rs), Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    #[Route('/search', name: 'book_search', methods: ['GET'])]
    public function searchBook(Request $request) {  
        $searchkey = $request->query->get('title');
        
        $books = $this->bookService->searchBookByTitle($searchkey);

        $rs = [
            "code"    => "200",
            "message" => "all Books",
            "data"    => $this->getBookDtoArray($books)
            // "data"    =>  $books
        ];
        return new Response($this->toJson($rs), Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    #[Route('/books', name: 'book_all', methods: ['GET'])]
    public function allBooks(): Response {
    	$books = $this->bookService->allBooks();

        $rs = [
            "code"    => "200",
            "message" => "all Books",
            "data"    => $this->getBookDtoArray($books)
            // "data"    =>  $books
        ];
        return new Response($this->toJson($rs), Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    public function getInputArray(Request $request){
        return json_decode($request->getContent(), true);
    }

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

    public function toJson($items){
        $serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);
        return $serializer->serialize($items, 'json', [
		    'circular_reference_handler' => function ($object) { return $object->getId(); },
            'ignored_attributes' => ['book']
	    ]);
    }

}

