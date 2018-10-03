<?php

namespace Wearesho\Notifications\Tests\Repository;

use GuzzleHttp;
use PHPUnit\Framework\TestCase;
use Wearesho\Notifications\Config;
use Wearesho\Notifications\Repository;

/**
 * Class AuthorizeTest
 * @package Wearesho\Notifications\Tests\Repository
 */
class AuthorizeTest extends TestCase
{
    /** @var GuzzleHttp\Handler\MockHandler */
    protected $mock;

    /** @var array */
    protected $container;

    /** @var Repository */
    protected $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mock = new GuzzleHttp\Handler\MockHandler();
        $this->container = [];

        $history = GuzzleHttp\Middleware::history($this->container);

        $stack = new GuzzleHttp\HandlerStack($this->mock);
        $stack->push($history);

        $this->repository = new Repository(
            new Config("https://google.com/", "test"),
            new GuzzleHttp\Client(['handler' => $stack,])
        );
    }

    /**
     * @expectedException GuzzleHttp\Exception\RequestException
     * @expectedExceptionMessage Missing response
     */
    public function testMissingResponse(): void
    {
        $this->mock->append(
            new GuzzleHttp\Exception\RequestException(
                'Missing response',
                new GuzzleHttp\Psr7\Request('GET', 'https://google.com/')
            )
        );

        $this->repository->authorize(1);
    }

    /**
     * @expectedException \Wearesho\Notifications\Exceptions\Credentials\Missed
     * @expectedExceptionMessage Missing service key
     */
    public function testMissingCredentials(): void
    {
        $this->mock->append(
            new GuzzleHttp\Exception\RequestException(
                '',
                new GuzzleHttp\Psr7\Request('GET', 'https://google.com/'),
                new GuzzleHttp\Psr7\Response(401)
            )
        );

        $this->repository->authorize(1);
    }

    /**
     * @expectedException \Wearesho\Notifications\Exceptions\Credentials\Invalid
     * @expectedExceptionMessage Invalid service key
     */
    public function testInvalidCredentials(): void
    {
        $this->mock->append(
            new GuzzleHttp\Exception\RequestException(
                '',
                new GuzzleHttp\Psr7\Request('GET', 'https://google.com/'),
                new GuzzleHttp\Psr7\Response(403)
            )
        );

        $this->repository->authorize(1);
    }

    /**
     * @expectedException GuzzleHttp\Exception\RequestException
     * @expectedExceptionMessage Missing response
     * @expectedExceptionCode 500
     */
    public function testUnknownHttpError(): void
    {
        $this->mock->append(
            new GuzzleHttp\Exception\RequestException(
                'Missing response',
                new GuzzleHttp\Psr7\Request('GET', 'https://google.com/'),
                new GuzzleHttp\Psr7\Response(500)
            )
        );

        $this->repository->authorize(1);
    }

    /**
     * @expectedException \Wearesho\Notifications\Exceptions\InvalidResponse
     * @expectedExceptionMessage Invalid response from server: invalid body
     */
    public function testInvalidResponse(): void
    {
        $this->mock->append(
            new GuzzleHttp\Psr7\Response(
                200,
                [],
                'invalid body'
            )
        );

        $this->repository->authorize(1);
    }

    /**
     * @expectedException \Wearesho\Notifications\Exceptions\InvalidResponse
     * @expectedExceptionMessage Invalid response from server: []
     */
    public function testMissingTokenInResponse(): void
    {
        $this->mock->append(
            new GuzzleHttp\Psr7\Response(
                200,
                [],
                '[]'
            )
        );

        $this->repository->authorize(1);
    }

    public function testCorrectPassing(): void
    {
        $this->mock->append(
            new GuzzleHttp\Psr7\Response(
                200,
                [],
                '{"token":"1337"}'
            )
        );

        $token = $this->repository->authorize(1);
        $this->assertEquals('1337', $token);

        $this->assertCount(1, $this->container);

        /** @var GuzzleHttp\Psr7\Request $request */
        $request = $this->container[0]['request'];

        $this->assertEquals('userId=1', $request->getUri()->getQuery());
        $this->assertArrayHasKey('X-Authorization', $request->getHeaders());
        $this->assertEquals('test', $request->getHeaders()['X-Authorization'][0]);
    }
}
