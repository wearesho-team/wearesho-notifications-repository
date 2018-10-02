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

    /** @var bool */
    protected $read;

    public function __construct(
        int $user,
        string $message,
        array $context = [],
        string $type = null,
        \DateTime $time = null,
        bool $read = false
    ) {
        $this->user = $user;
        $this->setType($type);
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

    protected function setType(string $type = null): Notification
    {
        if ($type && !in_array($type, [
            Notification\Type::SUCCESS,
            Notification\Type::DANGER,
            Notification\Type::INFO,
            Notification\Type::PRIMARY,
            Notification\Type::SECONDARY,
            Notification\Type::WARNING,
        ])) {
            throw new \InvalidArgumentException("Invalid notification type {$type}");
        }

        $this->type = $type;

        return $this;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getTime(): ?\DateTime
    {
        return $this->time;
    }

    public function isRead(): bool
    {
        return $this->read;
    }

    public function getContext(): array
    {
        return $this->context;
    }

    /**
     * @inheritdoc
     */
    public function jsonSerialize(): array
    {
        return [
            'user' => $this->user,
            'type' => $this->type,
            'message' => $this->message,
            'time' => $this->time ? $this->time->format('Y-m-d H:i:s') : null,
            'read' => $this->read,
            'context' => $this->context,
        ];
    }
}
