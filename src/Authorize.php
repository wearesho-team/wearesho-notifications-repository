<?php

namespace Wearesho\Notifications;

/**
 * Interface Authorize
 * @package Wearesho\Notifications
 *
 * Role of this interface is to generate token for specified user
 */
interface Authorize
{
    /**
     * @param int $userId
     * @return string
     */
    public function authorize(int $userId): string;
}
