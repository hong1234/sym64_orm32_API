<?php

namespace App\Repository;

use App\Entity\Review;
use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

class ReviewRepository extends ServiceEntityRepository { 
    private $manager;

    // public function __construct(ManagerRegistry $registry, EntityManagerInterface $manager) {
    //     parent::__construct($registry, Review::class);
    //     $this->manager = $manager;
    // }

    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Review::class);
        $this->manager = $this->getEntityManager();
    }

    public function saveReview(Book $book, $data) {
        $name    = $data['name'];
        $email   = $data['email'];
        $content = $data['content'];

        $review = new Review();
        $review->setName($name)
               ->setEmail($email)
               ->setContent($content)
                ->setCreatedOn(new \DateTime("now"))
                ->setBook($book);
        $this->manager->persist($review);
        $this->manager->flush(); 
        return $review;
    }

    public function updateReview(Review $review, $data) {
        $review->setName($data['name'])
                ->setEmail($data['email'])
                ->setContent($data['content'])
                ->setUpdatedOn(new \DateTime("now"));
        $this->manager->flush();
    }

    public function removeReview(Review $review) {
        $this->manager->remove($review);
        $this->manager->flush();
    }

}
