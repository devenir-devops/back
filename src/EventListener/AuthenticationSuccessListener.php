<?php

namespace App\EventListener;

use App\Document\User;
use DateTime;
use Doctrine\ODM\MongoDB\DocumentManager;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTAuthenticatedEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: "lexik_jwt_authentication.on_jwt_authenticated")]
final class AuthenticationSuccessListener
{

    private DocumentManager $documentManager;
    private LoggerInterface $logger;

    public function __construct(DocumentManager $documentManager, LoggerInterface $logger)
    {
        $this->documentManager = $documentManager;
        $this->logger = $logger;
    }

    public function __invoke(JWTAuthenticatedEvent $event): void
    {
        $jwt_user = $event->getToken()->getUser();
        if ($jwt_user && !$jwt_user instanceof \App\Security\User) {
            $this->logger->error("User was not of type App\Security\User, was: %s", [get_class($jwt_user)]);
            return;
        } elseif ($jwt_user instanceof \App\Security\User) {
            $user = $this->documentManager->getRepository(User::class)->findOneBy(['email' => $jwt_user->getEmail()]);
            if (!$user) {
                $user = new User();
                $user->setCognitoId($jwt_user->getUserIdentifier());
                $user->setEmail($jwt_user->getEmail());
                $user->setIsSubscribedToNewsletter(false);
                $user->setLastLogin(new DateTime());
                $user->setFirstLogin(new DateTime());
                $this->documentManager->persist($user);
                $this->documentManager->flush();
            } else {
                $user->setLastLogin(new DateTime());
                $this->documentManager->persist($user);
                $this->documentManager->flush();
            }
        }

    }
}