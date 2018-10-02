<?php

namespace Wearesho\Notifications;

/**
 * Class Notification
 * @package Wearesho\Notifications
 */
class Notification
{
    protected $id;

    protected $type;

    protected $message;

    protected $time;

    protected $read;

    public function __construct(
        string $id,
        string $type,
        string $message,
        string $time,
        bool $read
    ) {
        $this->id = $id;
        $this->type = $type;
        $this->message = $message;
        $this->time = $time;
        $this->read = $read;
    }
}
