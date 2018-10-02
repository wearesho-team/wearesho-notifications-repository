<?php

namespace Wearesho\Notifications\Exceptions;

use Throwable;
use Wearesho\Notifications\Notification;

/**
 * Class InvalidNotification
 * @package Wearesho\Notifications\Exceptions
 */
class InvalidNotification extends \Exception
{
    /** @var Notification */
    protected $notification;

    public function __construct(Notification $notification, string $message, int $code = 0, Throwable $previous = null)
    {
        $message = "Error during saving notification: {$message}. Notification: "
            . json_encode($notification->jsonSerialize());
        parent::__construct($message, $code, $previous);
        $this->notification = $notification;
    }

    public function getNotification(): Notification
    {
        return $this->notification;
    }
}
