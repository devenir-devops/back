<?php

namespace App\DataFixtures;

use App\Document\Career;
use Doctrine\Bundle\MongoDBBundle\Fixture\Fixture as MongoDBFixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends MongoDBFixture
{
    public function load(ObjectManager $manager): void
    {
         $career = new Career();
         $career->setName('Web Developer');
         $manager->persist($career);

        $manager->flush();
    }
}
