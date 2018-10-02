<?php

namespace Wearesho\Notifications;

use GuzzleHttp;

/**
 * Class Repository
 * @package Wearesho\Notifications
 */
class Repository
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
                    throw new Exceptions\MissingCredentials(
                        "Missing service key",
                        0,
                        $exception
                    );
                case 403:
                    throw new Exceptions\InvalidCredentials(
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

    public function push(Notification $notification): void
    {

    }

    protected function getHeaders(): array
    {
        return [
            'X-Authorization' => $this->config->getServiceKey(),
        ];
    }
}
