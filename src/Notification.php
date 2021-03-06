<?php

namespace Wearesho\Notifications;

/**
 * Class Notification
 * @package Wearesho\Notifications
 */
class Notification implements \JsonSerializable
{
    /** @var int */
    protected $user;

    /** @var string */
    protected $type;

    /** @var string */
    protected $message;

    /** @var array */
    protected $context;

    /** @var \DateTime|null */
    protected $time;

    /** @var bool|null */
    protected $read;

    public function __construct(
        int $user,
        string $message,
        array $context = null,
        string $type = null,
        \DateTime $time = null,
        bool $read = null
    ) {
        $this->user = $user;
        $this->type = $type;
        $this->message = $message;
        $this->context = $context;
        $this->time = $time;
        $this->read = $read;
    }

    public function getUser(): int
    {
        return $this->user;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getTime(): ?\DateTime
    {
        return $this->time;
    }

    public function isRead(): ?bool
    {
        return $this->read;
    }

    public function getContext(): ?array
    {
        return $this->context;
    }

    /**
     * @inheritdoc
     */
    public function jsonSerialize(): array
    {
        $data = [
            'user' => $this->user,
            'type' => $this->type,
            'message' => $this->message,
            'time' => $this->time ? $this->time->format('Y-m-d H:i:s') : null,
            'read' => $this->read,
            'context' => $this->context,
        ];

        return array_filter($data, function ($value) {
            return !is_null($value);
        });
    }
}
