<?php

namespace Wearesho\Notifications\Exceptions;

use GuzzleHttp\Psr7\Response;
use Throwable;

/**
 * Class InvalidResponse
 * @package Wearesho\Notifications\Exceptions
 */
class InvalidResponse extends \Exception
{
    /** @var Response */
    protected $response;

    public function __construct(Response $response, int $code = 0, Throwable $previous = null)
    {
        $message = 'Invalid response from server: ' . (string)$response->getBody();
        parent::__construct($message, $code, $previous);
        $this->response = $response;
    }

    public function getResponse(): Response
    {
        return $this->response;
    }
}
