<?php

namespace App\Controller;

use App\Document\User;
use Doctrine\ODM\MongoDB\DocumentManager;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/users', name: 'app_api')]
class UserController extends AbstractController
{
    #[Route('/me/subscribe-to-newsletter', name: 'app_api_user')]
    public function subscribeToNewsletter(DocumentManager $documentManager, LoggerInterface $logger): Response
    {
        $securityUser = $this->getUser();
        if ($securityUser instanceof \App\Security\User) {
            $user = $documentManager->getRepository(User::class)->findOneBy(['email' => $securityUser->getEmail()]);
            if ($user) {
                $user->setIsSubscribedToNewsletter(true);
                $documentManager->flush();
                return new JsonResponse(
                    ["success" => true], 200, ['Content-Type' => 'application/json']
                );
            }
        } else {
            if($securityUser) {
                $logger->error("User was not of type App\Security\User, was : " .get_class($securityUser) );
            }
        }
        return new JsonResponse(
            ["success" => false, "error" => "user not found"], 404, ['Content-Type' => 'application/json']
        );
    }

    #[Route('/', name: 'app_api_users')]
    public function users(DocumentManager $documentManager, SerializerInterface $serializer): Response
    {
        $context = (new ObjectNormalizerContextBuilder())
            ->withGroups('me')
            ->toArray();
        $users = $documentManager->getRepository(User::class)->findAll();
        return new Response(
            $serializer->serialize($users, JsonEncoder::FORMAT, $context), 200, ['Content-Type' => 'application/json']
        );
    }
}