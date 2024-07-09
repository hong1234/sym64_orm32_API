<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\HttpKernel\Log\DebugLoggerInterface;
use Symfony\Component\ErrorHandler\Exception\FlattenException;
// use Symfony\Component\ErrorHandler\Exception\MethodNotAllowedHttpException;

class ErrorController extends AbstractController {
    
    public function show(FlattenException $exception, DebugLoggerInterface $logger = null){

        $rs = [
            "code" => $exception->getStatusCode(), 
            "message" => $exception->getStatusText(),
            "error" => $exception->getStatusCode()
        ];

        return new Response(json_encode($rs), Response::HTTP_CREATED, ['Content-Type' => 'application/json']);
    }
}