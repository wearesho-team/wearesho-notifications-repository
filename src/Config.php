<?php

namespace Wearesho\Notifications;

/**
 * Class Config
 * @package Wearesho\Notifications
 */
class Config implements ConfigInterface
{
    /** @var string */
    protected $url;

    /** @var string|null */
    protected $serviceKey;

    public function __construct(string $url, string $serviceKey = null)
    {
        $this->url = $url;
        $this->serviceKey = $serviceKey;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getServiceKey(): ?string
    {
        return $this->serviceKey;
    }
}
