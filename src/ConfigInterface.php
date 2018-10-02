<?php

namespace Wearesho\Notifications;

/**
 * Interface ConfigInterface
 * @package Wearesho\Notifications
 */
interface ConfigInterface
{
    public function getUrl(): string;

    public function getServiceKey(): ?string;
}
