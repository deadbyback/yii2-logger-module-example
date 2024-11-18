<?php

namespace app\modules\logger\interfaces;

use app\modules\logger\enums\LoggerType;

interface LoggerInterface
{
    /**
     * Sends message to current logger.
     *
     * @param string $message
     *
     * @return void
     */
    public function send(string $message): void;

    /**
     * Sends message by selected logger.
     *
     * @param string $message
     * @param LoggerType $loggerType
     *
     * @return void
     */
    public function sendByLogger(string $message, LoggerType $loggerType): void;

    /**
     * Gets current logger type.
     *
     * @return LoggerType|string
     */
    public function getType(): LoggerType|string;

    /**
     * Sets current logger type.
     * In PHP < 8.0 this method must contain setting inner property:
     * $this->type = $type;
     *
     * @param LoggerType $type
     */
    public function setType(LoggerType $type): void;
}