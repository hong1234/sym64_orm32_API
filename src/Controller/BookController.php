<?php
namespace App\Controller;

use App\Service\BookService;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api')]
class BookController extends AbstractController
{
    private $bookService;
   
    public function __construct(BookService $bookService) {
        $this->bookService = $bookService;
    }

    #[Route('/books', name: 'book_add', methods: ['POST'])]
    public function addBook(Request $request): Response {
        $data = json_decode($request->getContent(), true);
        $bookJson = $this->bookService->addBook($data);
        return new Response($bookJson, Response::HTTP_CREATED, ['Content-Type' => 'application/json']);
    }

    #[Route('/books/{bookId}', name: 'book_update', methods: ['PUT'], requirements: ['bookId' => '\d+'])]
    public function updateBook($bookId, Request $request): Response {   
        $data = json_decode($request->getContent(), true);
        $updatedBookJson = $this->bookService->updateBook($bookId, $data);
        if ($updatedBookJson==null)
            return new Response(json_encode(['error' => 'Book not found']), Response::HTTP_NOT_FOUND);
        return new Response($updatedBookJson, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    #[Route('/books/{bookId}', name: 'book_delete', methods: ['DELETE'], requirements: ['bookId' => '\d+'])]
    public function deleteBook($bookId): Response {   
        $status = $this->bookService->deleteBook($bookId);
        if($status==null)
            return new Response(json_encode(['error' => 'Book not found']), Response::HTTP_NOT_FOUND);
        return new Response(json_encode(['status' => 'Book deleted']), Response::HTTP_OK);
    }

    #[Route('/books/{bookId}', name: 'book_show', methods: ['GET'], requirements: ['bookId' => '\d+'])]
    public function showBook(int $bookId): Response {  
        $bookJson = $this->bookService->getBook($bookId);
        if($bookJson==null)
            return new Response(json_encode(['error' => 'Book not found']), Response::HTTP_NOT_FOUND);
        return new Response($bookJson, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    #[Route('/search', name: 'book_search', methods: ['GET'])]
    public function searchBook(Request $request) {  
        $searchkey = $request->query->get('title');
        $booksJson = $this->bookService->searchBookByTitle($searchkey);
        return new Response($booksJson, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    #[Route('/books', name: 'book_all', methods: ['GET'])]
    public function allBooks(): Response {
    	$booksJson = $this->bookService->allBooks();
        return new Response($booksJson, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

}

