<?php

namespace App\Tests\Controller;

use App\Security\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testUserMe(): void
    {

        $client = static::createClient();
        $client->loginUser(new User('testUser', ['ROLE_USER'], 'test@example.com'));
        $crawler = $client->request('GET', '/api/users/me');
        $this->assertResponseIsSuccessful();
        $content = $client->getResponse()->getContent();
        $this->assertJson($content);
    }

    public function testNotFoundUserMe(): void
    {

        $client = static::createClient();
        $client->loginUser(new User('testUser', ['ROLE_USER'], 'test_missiong@example.com'));
        $crawler = $client->request('GET', '/api/users/me');
        $this->assertResponseStatusCodeSame(404, "User not found");

        $content = $client->getResponse()->getContent();
        $this->assertJson($content);
    }

}
