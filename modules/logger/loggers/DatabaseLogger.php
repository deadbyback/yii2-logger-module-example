<?php

namespace app\modules\logger\loggers;

use app\modules\logger\enums\LoggerType;
use app\modules\logger\factories\LoggerFactory;
use app\modules\logger\interfaces\LoggerInterface;
use app\modules\logger\jobs\BatchLogJob;
use yii\queue\Queue;

final class DatabaseLogger implements LoggerInterface
{
    private const BATCH_SIZE = 100;
    private array $messageQueue = [];

    public function __construct(
        private readonly Queue $queue,
        private readonly int $batchSize = self::BATCH_SIZE
    ) {}

    public function send(string $message): void
    {
        $this->messageQueue[] = [
            'message' => $message,
            'created_at' => date('Y-m-d H:i:s'),
            'level' => 'info'
        ];

        if (count($this->messageQueue) >= $this->batchSize) {
            $this->flush();
        }
    }

    public function flush(): void
    {
        if (empty($this->messageQueue)) {
            return;
        }

        $this->queue->push(new BatchLogJob([
            'messages' => $this->messageQueue
        ]));

        $this->messageQueue = [];
    }

    public function __destruct()
    {
        $this->flush();
    }

    public function sendByLogger(string $message, LoggerType $loggerType): void
    {
        $logger = LoggerFactory::create($loggerType);
        $logger->send($message);
    }

    public function getType(): LoggerType
    {
        return LoggerType::DATABASE;
    }

    public function setType(LoggerType $type): void {}
}