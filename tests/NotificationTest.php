<?php

namespace Wearesho\Notifications\Tests;

use PHPUnit\Framework\TestCase;
use Wearesho\Notifications\Notification;

/**
 * Class NotificationTest
 * @package Wearesho\Notifications\Tests
 */
class NotificationTest extends TestCase
{
    public function testGetters(): void
    {
        $dateTime = new \DateTime();
        $notification = new Notification(1, 'test message', ['a' => 5,], 'type', $dateTime, true);
        $this->assertEquals(1, $notification->getUser());
        $this->assertEquals('test message', $notification->getMessage());
        $this->assertEquals(['a' => 5,], $notification->getContext());
        $this->assertEquals('type', $notification->getType());
        $this->assertEquals($dateTime, $notification->getTime());
        $this->assertTrue($notification->isRead());
    }

    public function testDefaultValues(): void
    {
        $notification = new Notification(1, 'test');
        $this->assertNull($notification->getType());
        $this->assertNull($notification->isRead());
        $this->assertNull($notification->getContext());
        $this->assertNull($notification->getTime());
    }
}
