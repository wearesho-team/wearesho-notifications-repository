<?php

namespace Wearesho\Notifications\Push;

use Wearesho\Notifications;

/**
 * Class Filter
 * @package Wearesho\Notifications\Push
 */
class Filter implements Notifications\Push
{
    /** @var string[] */
    public $types;

    /** @var Notifications\Push */
    protected $target;

    public function __construct(Notifications\Push $target, array $types = [])
    {
        $this->target = $target;
        $this->types = $types;
    }

    /**
     * @inheritdoc
     */
    public function push(Notifications\Notification $notification): void
    {
        if (!in_array($notification->getType(), $this->types)) {
            return;
        }
        $this->target->push($notification);
    }
}
