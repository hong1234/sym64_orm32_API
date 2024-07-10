<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Serializer;

class BaseController extends AbstractController {

    public function getInputArray(Request $request){
        return json_decode($request->getContent(), true);
    }

    public function toJson($items){
        $serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);
        return $serializer->serialize($items, 'json', [
		    'circular_reference_handler' => function ($object) { return $object->getId(); },
            'ignored_attributes' => ['book']
	    ]);
    }
}