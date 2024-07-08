<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

class BookRepository extends ServiceEntityRepository { 
    private $manager;

    // public function __construct(EntityManagerInterface $manager){
    //     $this->manager = $manager;
    // }

    // public function __construct(ManagerRegistry $registry, EntityManagerInterface $manager){
    //     parent::__construct($registry, Book::class);
    //     $this->manager = $manager;
    // }

    public function __construct(ManagerRegistry $registry){
        parent::__construct($registry, Book::class);
        $this->manager = $this->getEntityManager();
    }

    public function saveBook($data){
        $title   = $data['title'];
        $content = $data['content'];

        $book = new Book();
        $book->setTitle($title)
             ->setContent($content)
             ->setCreatedOn(new \DateTime("now"));

        $this->manager->persist($book);
        $this->manager->flush();  
        return $book;    
    }

    public function updateBook(Book $book, $data){
        $book->setTitle($data['title'])
            ->setContent($data['content'])
            ->setUpdatedOn(new \DateTime("now"));
        $this->manager->flush(); 
        return $book;  
    }

    public function removeBook(Book $book){
        $this->manager->remove($book);
        $this->manager->flush();
    }

    public function searchBook(String $searchkey){
        return $this->createQueryBuilder('b')
        ->where('b.title LIKE :searchkey')
        ->setParameter('searchkey', '%'.$searchkey.'%')
        ->orderBy('b.id', 'ASC')
        ->getQuery()
        ->getResult();      
    }

}
