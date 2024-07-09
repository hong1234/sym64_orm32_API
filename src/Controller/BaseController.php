<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Serializer;

use App\Dto\BookDto;
use App\Dto\ReviewDto;

class BaseController extends AbstractController {

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

    public function toJson($items){
        $serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);
        return $serializer->serialize($items, 'json', [
		    'circular_reference_handler' => function ($object) { return $object->getId(); },
            'ignored_attributes' => ['book']
	    ]);
    }
}