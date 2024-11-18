<?php

declare(strict_types=1);

namespace app\modules\logger\loggers;

use app\modules\logger\enums\LoggerType;
use app\modules\logger\exceptions\EmailLoggerException;
use app\modules\logger\factories\LoggerFactory;
use app\modules\logger\interfaces\LoggerInterface;

use yii\mail\MailerInterface;

final class EmailLogger implements LoggerInterface
{
    public function __construct(
        private readonly MailerInterface $mailer,
        private readonly string          $recipientEmail = '',
        private readonly string          $subject = 'Log Message'
    ) {
        if (empty($this->recipientEmail)) {
            throw new EmailLoggerException(
                'Recipient email is not configured'
            );
        }
    }

    public function send(string $message): void
    {
        $timestamp = date('Y-m-d H:i:s');
        $formattedMessage = "[$timestamp] $message";

        $sent = $this->mailer
            ->compose()
            ->setTo($this->recipientEmail)
            ->setSubject($this->subject)
            ->setTextBody($formattedMessage)
            ->send();

        if (!$sent) {
            throw new EmailLoggerException('Failed to send log message via email');
        }

        echo 'EmailLogger | '. $formattedMessage;
    }

    public function sendByLogger(string $message, LoggerType $loggerType): void
    {
        $logger = LoggerFactory::create($loggerType);
        $logger->send($message);
    }

    public function getType(): LoggerType
    {
        return LoggerType::EMAIL;
    }

    public function setType(LoggerType $type): void {}
}