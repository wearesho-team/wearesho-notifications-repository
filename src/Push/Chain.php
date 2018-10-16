<?php

namespace Wearesho\Notifications\Push;

use Wearesho\Notifications;

/**
 * Class Chain
 * @package Wearesho\Notifications\Repository
 */
class Chain implements Notifications\Push
{
    /** @var Notifications\Push[] */
    protected $chain;

    /**
     * Chain constructor.
     * @param Notifications\Push[] $chain
     */
    public function __construct(array $chain = [])
    {
        foreach ($chain as $element) {
            $this->add($element);
        }
    }

    public function add(Notifications\Push $push)
    {
        $this->chain[] = $push;
    }

    /**
     * @inheritdoc
     */
    public function push(Notifications\Notification $notification): void
    {
        foreach ($this->chain as $element) {
            $element->push($notification);
        }
    }
}
