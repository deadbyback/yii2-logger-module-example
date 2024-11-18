<?php

namespace app\modules\logger\components;

use app\modules\logger\enums\LoggerType;
use app\modules\logger\interfaces\LoggerInterface;
use app\modules\logger\factories\LoggerFactory;
use Yii;

final class Logger implements LoggerInterface
{
    private readonly LoggerInterface $currentLogger;

    public function __construct(
        private LoggerType $type = LoggerType::FILE
    ) {
        $this->currentLogger = LoggerFactory::create($type);
    }

    public function send(string $message): void
    {
        $this->currentLogger->send($message);
    }

    public function sendByLogger(string $message, LoggerType $loggerType): void
    {
        $logger = LoggerFactory::create($loggerType);
        $logger->send($message);
    }

    public function getType(): LoggerType
    {
        return $this->type;
    }

    public function setType(LoggerType $type): void
    {
        $this->type = $type;
        $this->currentLogger = LoggerFactory::create($type);
    }
}