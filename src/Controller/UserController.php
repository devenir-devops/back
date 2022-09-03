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
            if ($securityUser) {
                $logger->error("User was not of type App\Security\User, was : " . get_class($securityUser));
            }
        }
        return new JsonResponse(
            ["success" => false, "error" => "user not found"], 404, ['Content-Type' => 'application/json']
        );
    }

    #[Route('/me', name: 'app_api_users_me')]
    public function users(DocumentManager $documentManager, SerializerInterface $serializer, LoggerInterface $logger): Response
    {
        $context = (new ObjectNormalizerContextBuilder())
            ->withGroups('me')
            ->toArray();
        $securityUser = $this->getUser();
        if ($securityUser && $securityUser instanceof \App\Security\User) {
            $user = $documentManager->getRepository(User::class)->findOneBy(['email' => $securityUser->getEmail()]);
            if ($user) {
                return new Response(
                    $serializer->serialize($user, JsonEncoder::FORMAT, $context), 200, ['Content-Type' => 'application/json']
                );
            } else {
                $logger->warning("User not found for email: " . $securityUser->getEmail());
                return new JsonResponse(
                    ["error" => "not found"], 404, ['Content-Type' => 'application/json']
                );
            }
        } else {
            return new JsonResponse(
                ["error" => "internal error"], 500, ['Content-Type' => 'application/json']
            );

        }
    }
}