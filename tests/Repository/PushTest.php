<?php

namespace Wearesho\Notifications\Tests\Repository;

use GuzzleHttp;
use PHPUnit\Framework\TestCase;
use Wearesho\Notifications\Config;
use Wearesho\Notifications\Notification;
use Wearesho\Notifications\Repository;

/**
 * Class PushTest
 * @package Wearesho\Notifications\Tests\Repository
 */
class PushTest extends TestCase
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

        $this->repository->push(new Notification(1, ""));
    }

    /**
     * @expectedException \Wearesho\Notifications\Exceptions\MissingCredentials
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

        $this->repository->push(new Notification(1, ""));
    }

    /**
     * @expectedException \Wearesho\Notifications\Exceptions\InvalidCredentials
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

        $this->repository->push(new Notification(1, ""));
    }

    /**
     * @expectedException \Wearesho\Notifications\Exceptions\InvalidNotification
     * @expectedExceptionMessage Notification: {"user":1,"message":"","read":false}
     */
    public function testInvalidNotification(): void
    {
        $this->mock->append(
            new GuzzleHttp\Exception\RequestException(
                'Missing user id',
                new GuzzleHttp\Psr7\Request('GET', 'https://google.com/'),
                new GuzzleHttp\Psr7\Response(400)
            )
        );

        $this->repository->push(new Notification(1, ""));
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

        $this->repository->push(new Notification(1, ""));
    }

    public function testCorrect(): void
    {
        $this->mock->append(new GuzzleHttp\Psr7\Response(200));
        $this->repository->push(new Notification(1, ""));
        $this->assertTrue(true);
    }
}
