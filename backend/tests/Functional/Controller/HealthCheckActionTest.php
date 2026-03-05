<?php

declare(strict_types=1);

namespace Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class HealthCheckActionTest extends WebTestCase
{
    public function test_request_response_successfully(): void
    {
        // arrange
        $client = self::createClient();

        // act
        /** @var RouterInterface $router */
        $router = self::getContainer()->get('router');
        $client->jsonRequest(Request::METHOD_GET, $router->generate('healthcheck'));

        // assert
        self::assertResponseIsSuccessful();
        $content = $client->getResponse()->getContent();

        if ($content === false) {
            self::fail('Response content is false');
        }

        self::assertSame(
            ['status' => 'OK'],
            json_decode($content, true),
        );
    }
}
