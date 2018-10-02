<?php

namespace Wearesho\Notifications\Exceptions;

use Psr\Http\Message\ResponseInterface;

/**
 * Class InvalidResponse
 * @package Wearesho\Notifications\Exceptions
 */
class InvalidResponse extends \Exception
{
    /** @var ResponseInterface */
    protected $response;

    public function __construct(ResponseInterface $response, int $code = 0, \Throwable $previous = null)
    {
        $message = 'Invalid response from server: ' . (string)$response->getBody();
        parent::__construct($message, $code, $previous);
        $this->response = $response;
    }

    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }
}
