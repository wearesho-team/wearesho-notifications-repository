<?php

namespace Wearesho\Notifications\Tests\Exceptions;

use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Wearesho\Notifications\Exceptions\InvalidResponse;

/**
 * Class InvalidResponse
 * @package Wearesho\Notifications\Tests\Exceptions
 */
class InvalidResponseTest extends TestCase
{
    public function testGetter(): void
    {
        $response = new Response(400);
        $exception = new InvalidResponse($response);
        $this->assertEquals($response, $exception->getResponse());
    }
}
