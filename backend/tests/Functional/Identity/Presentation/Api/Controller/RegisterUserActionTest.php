<?php

declare(strict_types=1);

namespace App\Tests\Functional\Identity\Presentation\Api\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RegisterUserActionTest extends WebTestCase
{
    public function test_successful_registration(): void
    {
        // arrange
        $client = static::createClient();
        $router = static::getContainer()->get('router');

        // act
        $client->request(
            method: Request::METHOD_POST,
            uri: $router->generate('identity_register'),
            server: ['CONTENT_TYPE' => 'application/json'],
            content: json_encode([
                'email' => 'runner@example.com',
                'password' => 'secret123',
            ]),
        );

        // assert
        self::assertResponseStatusCodeSame(Response::HTTP_CREATED);
        self::assertResponseHeaderSame('content-type', 'application/json');

        $data = json_decode($client->getResponse()->getContent(), true);

        self::assertTrue($data['success']);
        self::assertArrayHasKey('id', $data['data']);
        self::assertNotEmpty($data['data']['id']);
    }
}
