<?php

declare(strict_types=1);

namespace App\Tests\Functional\Identity\Presentation\Api\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class RegisterUserActionTest extends WebTestCase
{
    public function test_successful_registration(): void
    {
        // arrange
        $client = static::createClient();

        /** @var UrlGeneratorInterface $router */
        $router = static::getContainer()->get('router');

        // act
        $client->request(
            method: Request::METHOD_POST,
            uri: $router->generate('identity_register'),
            server: ['CONTENT_TYPE' => 'application/json'],
            content: json_encode([
                'email' => 'runner@example.com',
                'password' => 'secret123',
            ], JSON_THROW_ON_ERROR),
        );

        // assert
        self::assertResponseStatusCodeSame(Response::HTTP_CREATED);
        self::assertResponseHeaderSame('content-type', 'application/json');

        $responseContent = $client->getResponse()->getContent();
        self::assertNotFalse($responseContent);
        $data = json_decode($responseContent, true);

        self::assertTrue($data['success']);
        self::assertArrayHasKey('id', $data['data']);
        self::assertNotEmpty($data['data']['id']);
    }
}
