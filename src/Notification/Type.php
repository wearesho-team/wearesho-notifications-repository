<?php

namespace Wearesho\Notifications\Notification;

/**
 * Interface Type
 * @package Wearesho\Notifications\Notification
 */
interface Type
{
    public const PRIMARY = 'primary';
    public const SECONDARY = 'secondary';
    public const INFO = 'info';
    public const SUCCESS = 'success';
    public const DANGER = 'danger';
    public const WARNING = 'warning';
}
