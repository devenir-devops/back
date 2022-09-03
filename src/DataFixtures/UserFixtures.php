<?php

namespace App\DataFixtures;

use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\MongoDBBundle\Fixture\Fixture as MongoDBFixture;

class UserFixtures extends MongoDBFixture {

        public function load(ObjectManager $manager): void
        {
            $testUser = new \App\Document\User();
            $testUser->setEmail('test@example.com');
            $testUser->setCognitoId("test");
            $testUser->setIsSubscribedToNewsletter(false);
            $testUser->setFirstLogin(new \DateTime());
            $testUser->setLastLogin(new \DateTime());

            $manager->persist($testUser);
            $manager->flush();
        }
}