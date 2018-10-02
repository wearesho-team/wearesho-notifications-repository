<?php

namespace Wearesho\Notifications;

use Horat1us\Environment;

/**
 * Class EnvironmentConfig
 * @package Wearesho\Notifications
 */
class EnvironmentConfig extends Environment\Config implements ConfigInterface
{
    public function __construct(string $keyPrefix = 'WEARESHO_NOTIFICATIONS_')
    {
        parent::__construct($keyPrefix);
    }

    public function getUrl(): string
    {
        return $this->getEnv('URL');
    }

    public function getServiceKey(): ?string
    {
        return $this->getEnv('SERVICE_KEY', [$this, 'null',]);
    }
}
