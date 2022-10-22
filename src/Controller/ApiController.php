<?php

namespace App\Controller;

use App\Document\Career;
use App\Security\User;
use Doctrine\ODM\MongoDB\DocumentManager;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ApiController extends AbstractController
{
    #[Route('/api', name: 'app_api')]
    public function index(LoggerInterface $logger): Response
    {
        $user = $this->getUser();
        if ($user instanceof User) {
            return new JsonResponse(
                ["email" => $user->getEmail()]
            );
        } else {
            if ($user) {
                $logger->error("User was not of type App\Security\User, was: %s", [get_class($user)]);
            }
            return new JsonResponse(

                ["error" => "internal error"],
                500,
                ['Content-Type' => 'application/json']
            );
        }
    }

    #[Route('/careers', name: 'app_api_careers')]
    public function careers(DocumentManager $documentManager, SerializerInterface $serializer): Response
    {
        $careers = $documentManager->getRepository(Career::class)->findAll();

        return new Response(
            $serializer->serialize($careers, 'json'),
            200,
            ['Content-Type' => 'application/json']
        );
    }


}
