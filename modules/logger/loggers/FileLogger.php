<?php

declare(strict_types=1);

namespace app\modules\logger\loggers;

use app\modules\logger\exceptions\FileLoggerException;
use app\modules\logger\factories\LoggerFactory;
use app\modules\logger\interfaces\LoggerInterface;
use app\modules\logger\enums\LoggerType;
use Yii;

final class FileLogger implements LoggerInterface
{
    private string $logFile;

    public function __construct(string $logFile = null)
    {
        $this->logFile = $logFile ?? Yii::getAlias('@runtime/logs/app.log');
    }

    public function send(string $message): void
    {
        $timestamp = date('Y-m-d H:i:s');
        $formattedMessage = "[$timestamp] $message" . PHP_EOL;

        $directory = dirname($this->logFile);
        if (!is_dir($directory)) {
            if (!mkdir($directory, 0777, true) && !is_dir($directory)) {
                throw new FileLoggerException(
                    sprintf('Directory "%s" was not created', $directory)
                );
            }
        }

        if (false === file_put_contents($this->logFile, $formattedMessage, FILE_APPEND | LOCK_EX)) {
            throw new FileLoggerException(
                sprintf('Unable to write to log file: %s', $this->logFile)
            );
        }
    }

    public function sendByLogger(string $message, LoggerType $loggerType): void
    {
        $logger = LoggerFactory::create($loggerType);
        $logger->send($message);
    }

    public function getType(): LoggerType
    {
        return LoggerType::FILE;
    }

    public function setType(LoggerType $type): void {}
}