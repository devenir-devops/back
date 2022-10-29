<?php

namespace App\Tests\Controller;

use App\Document\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testUserMe(): void
    {

        $client = static::createClient();
        $client->loginUser(User::createFromPayload('test@example.com', array('sub' => 'abc123')));
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
                $this->assertArrayHasKey('cognito_id', $response_parsed);
            }
        }

    }


}
