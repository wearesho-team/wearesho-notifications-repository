<?php

namespace Wearesho\Notifications\Tests\Push;

use PHPUnit\Framework\TestCase;
use Wearesho\Notifications;

/**
 * Class FilterTest
 * @package Wearesho\Notifications\Tests\Push
 */
class FilterTest extends TestCase
{
    public function testFiltering(): void
    {
        $mock = new Notifications\Push\Mock();
        $filter = new Notifications\Push\Filter(
            $mock,
            [Notifications\Notification\Type::PRIMARY]
        );

        $primaryNotification = new Notifications\Notification(
            1,
            'Hello, World!',
            [],
            Notifications\Notification\Type::PRIMARY
        );
        $secondaryNotification = new Notifications\Notification(
            1,
            'Got it!',
            [],
            Notifications\Notification\Type::SECONDARY
        );

        $filter->push($primaryNotification);
        $filter->push($secondaryNotification);

        $this->assertCount(1, $mock->history);
        $this->assertEquals($primaryNotification, $mock->history[0]);
    }
}
