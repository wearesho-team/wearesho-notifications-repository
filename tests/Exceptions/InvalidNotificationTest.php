<?php

namespace Wearesho\Notifications\Tests\Exceptions;

use PHPUnit\Framework\TestCase;
use Wearesho\Notifications\Exceptions\InvalidNotification;
use Wearesho\Notifications\Notification;

/**
 * Class InvalidNotificationTest
 * @package Wearesho\Notifications\Tests\Exceptions
 */
class InvalidNotificationTest extends TestCase
{
    public function testGetter(): void
    {
        $notification = new Notification(1, 'asdf');
        $exception = new InvalidNotification($notification, 'error');
        $this->assertEquals($notification, $exception->getNotification());
    }
}
