<?php

namespace Wearesho\Notifications\Tests\Push;

use PHPUnit\Framework\TestCase;
use Wearesho\Notifications;

/**
 * Class MockTest
 * @package Wearesho\Notifications\Tests\Push
 */
class MockTest extends TestCase
{
    public function testPush(): void
    {
        $mock = new Notifications\Push\Mock();
        $notification = new Notifications\Notification(1, 'Hello world!');
        $mock->push($notification);
        $this->assertCount(1, $mock->history);
        $this->assertEquals($notification, $mock->history[0]);
    }
}
