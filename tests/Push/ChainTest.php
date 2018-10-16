<?php

namespace Wearesho\Notifications\Tests\Push;

use PHPUnit\Framework\TestCase;
use Wearesho\Notifications;

/**
 * Class ChainTest
 * @package Wearesho\Notifications\Tests\Push
 */
class ChainTest extends TestCase
{
    public function testChain(): void
    {
        $mock = new Notifications\Push\Mock();
        $chain = new Notifications\Push\Chain([$mock]);
        $chain->add($mock);

        $notification = new Notifications\Notification(1, 'Hello World');
        $chain->push($notification);

        $this->assertCount(2, $mock->history);
        $this->assertEquals($notification, $mock->history[0]);
        $this->assertEquals($notification, $mock->history[1]);
    }
}
