<?php

namespace Wearesho\Notifications\Push;

use Wearesho\Notifications;

/**
 * Class Mock
 * @package Wearesho\Notifications\Push
 */
class Mock implements Notifications\Push
{
    /** @var Notifications\Notification[] */
    public $history = [];

    /**
     * @inheritdoc
     */
    public function push(Notifications\Notification $notification): void
    {
        $this->history[] = $notification;
    }
}
