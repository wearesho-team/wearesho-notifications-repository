<?php

namespace Wearesho\Notifications;

use GuzzleHttp;
use Wearesho\Notifications\Exceptions\InvalidNotification;

/**
 * Class Repository
 * @package Wearesho\Notifications
 */
class Repository implements Authorize, Push
{
    /** @var ConfigInterface */
    protected $config;

    /** @var GuzzleHttp\ClientInterface */
    protected $guzzleClient;

    public function __construct(ConfigInterface $config, GuzzleHttp\ClientInterface $client)
    {
        $this->config = $config;
        $this->guzzleClient = $client;
    }

    /**
     * @param int $userId
     * @return string
     * @throws Exceptions\InvalidResponse
     * @throws Exceptions\Credentials\Invalid
     * @throws Exceptions\Credentials\Missed
     * @throws GuzzleHttp\Exception\GuzzleException
     */
    public function authorize(int $userId): string
    {
        try {
            $response = $this->guzzleClient->request(
                'GET',
                rtrim($this->config->getUrl(), '/') . '/service/authorize',
                [
                    GuzzleHttp\RequestOptions::HEADERS => $this->getHeaders(),
                    GuzzleHttp\RequestOptions::QUERY => [
                        'userId' => $userId,
                    ],
                ]
            );
        } catch (GuzzleHttp\Exception\RequestException $exception) {
            $response = $exception->getResponse();
            if (!$response) {
                throw $exception;
            }

            switch ($response->getStatusCode()) {
                case 401:
                    throw new Exceptions\Credentials\Missed(
                        "Missing service key",
                        0,
                        $exception
                    );
                case 403:
                    throw new Exceptions\Credentials\Invalid(
                        "Invalid service key passed",
                        0,
                        $exception
                    );
            }
            throw $exception;
        }

        $body = json_decode((string)$response->getBody(), true);
        if (json_last_error() !== JSON_ERROR_NONE || !array_key_exists('token', $body)) {
            throw new Exceptions\InvalidResponse($response);
        }

        return $body['token'];
    }

    /**
     * @param Notification $notification
     * @throws Exceptions\Credentials\Invalid
     * @throws Exceptions\Credentials\Missed
     * @throws InvalidNotification
     * @throws GuzzleHttp\Exception\GuzzleException
     */
    public function push(Notification $notification): void
    {
        try {
            $this->guzzleClient->send(
                new GuzzleHttp\Psr7\Request(
                    'POST',
                    rtrim($this->config->getUrl(), '/') . '/service/notification',
                    $this->getHeaders(),
                    json_encode($notification)
                )
            );
        } catch (GuzzleHttp\Exception\RequestException $exception) {
            $response = $exception->getResponse();
            if (!$response) {
                throw $exception;
            }

            switch ($response->getStatusCode()) {
                case 401:
                    throw new Exceptions\Credentials\Missed(
                        "Missing service key",
                        0,
                        $exception
                    );
                case 403:
                    throw new Exceptions\Credentials\Invalid(
                        "Invalid service key passed",
                        0,
                        $exception
                    );
                case 400:
                    throw new Exceptions\InvalidNotification(
                        $notification,
                        $exception->getMessage(),
                        $exception->getCode(),
                        $exception
                    );
            }

            throw $exception;
        }
    }

    protected function getHeaders(): array
    {
        return [
            'Content-Type' => 'application/json',
            'X-Authorization' => $this->config->getServiceKey(),
        ];
    }
}
