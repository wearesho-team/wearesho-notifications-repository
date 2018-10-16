<?php

namespace Wearesho\Notifications;

use Wearesho\Notifications\Exceptions\InvalidNotification;

/**
 * Interface Push
 * @package Wearesho\Notifications
 *
 * Role of this interface is to push notification to remote server
 */
interface Push
{
    /**
     * @param Notification $notification
     * @throws InvalidNotification
     */
    public function push(Notification $notification): void;
}
