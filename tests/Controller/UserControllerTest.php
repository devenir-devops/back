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
        $client->request('GET', '/api/users/me');
        $this->assertResponseIsSuccessful();
        $content = $client->getResponse()->getContent();
        $this->assertNotEmpty($content);
        /** @phpstan-ignore-next-line */
        $this->assertJson($content);
        if (is_string($content)) {
            $response_parsed = \Safe\json_decode($content, true);
            if (is_array($response_parsed)) {
                $this->assertArrayHasKey('email', $response_parsed);
                $this->assertArrayHasKey('is_subscribed_to_newsletter', $response_parsed);
                $this->assertArrayHasKey('first_login', $response_parsed);
                $this->assertArrayHasKey('last_login', $response_parsed);
                $this->assertArrayHasKey('cognito_id', $response_parsed);
                $this->assertArrayHasKey('id', $response_parsed);
            }
        }

    }

    public function testNotFoundUserMe(): void
    {

        $client = static::createClient();
        $client->loginUser(new User('testUser', ['ROLE_USER'], 'john_doe@doe.com'));
        $client->request('GET', '/api/users/me');
        $this->assertResponseStatusCodeSame(404, "User not found");

        $content = $client->getResponse()->getContent();
        $this->assertNotEmpty($content);
        /** @phpstan-ignore-next-line */
        $this->assertJson($content);
    }

}
