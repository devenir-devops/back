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
        $jwtUser = $event->getToken()->getUser();
        if ($jwtUser && !$jwtUser instanceof User) {
            $this->logger->error(
                sprintf("User was not of type App\Document\User, was: %s", get_class($jwtUser)),
                [$jwtUser]
            );
            return;
        }
        $user = $this->documentManager->getRepository(User::class)->findOneBy(['email' => $jwtUser->getEmail()]);
        if (!$user) {
            $jwtUser->setLastLogin(new DateTime());
            $jwtUser->setFirstLogin(new DateTime());
            $this->documentManager->persist($jwtUser);
            $this->documentManager->flush();
            $event->getToken()->setUser($jwtUser);
        } else {
            $this->logger->info(sprintf("Returning user: %s", $user->getEmail()), [$user]);

            $user->setLastLogin(new DateTime());
            $this->documentManager->persist($user);
            $this->documentManager->flush();
            $event->getToken()->setUser($user);
        }
        $this->logger->info(sprintf("User logged in %s", $jwtUser->getEmail()), [$jwtUser]);

    }
}
