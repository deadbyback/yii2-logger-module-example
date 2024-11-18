<?php

namespace unit\modules\logger;

use app\modules\logger\enums\LoggerType;
use app\modules\logger\exceptions\EmailLoggerException;
use app\modules\logger\loggers\EmailLogger;

use PHPUnit\Framework\TestCase;
use yii\mail\MailerInterface;
use yii\mail\MessageInterface;

class EmailLoggerTest extends TestCase
{
    private const TEST_EMAIL = 'admin@example.com';
    private MailerInterface $mailerMock;
    private MessageInterface $messageMock;
    private EmailLogger $logger;

    protected function setUp(): void
    {
        $this->messageMock = $this->createMock(MessageInterface::class);
        $this->mailerMock = $this->createMock(MailerInterface::class);
        $this->logger = new EmailLogger(
            mailer: $this->mailerMock,
            recipientEmail: self::TEST_EMAIL
        );
    }

    public function testSuccessfulEmailSend(): void
    {
        $testMessage = 'Test log message';

        $this->messageMock
            ->expects($this->once())
            ->method('setTo')
            ->with(self::TEST_EMAIL)
            ->willReturnSelf();

        $this->messageMock
            ->expects($this->once())
            ->method('setSubject')
            ->with('Log Message')
            ->willReturnSelf();

        $this->messageMock
            ->expects($this->once())
            ->method('setTextBody')
            ->with($this->matchesRegularExpression('/\[\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\] Test log message/'))
            ->willReturnSelf();

        $this->mailerMock
            ->expects($this->once())
            ->method('compose')
            ->willReturn($this->messageMock);

        $this->messageMock
            ->expects($this->once())
            ->method('send')
            ->willReturn(true);

        $this->logger->send($testMessage);
    }

    public function testFailedEmailSend(): void
    {
        $this->messageMock
            ->method('setTo')
            ->willReturnSelf();
        $this->messageMock
            ->method('setSubject')
            ->willReturnSelf();
        $this->messageMock
            ->method('setTextBody')
            ->willReturnSelf();

        $this->mailerMock
            ->method('compose')
            ->willReturn($this->messageMock);

        $this->messageMock
            ->method('send')
            ->willReturn(false);

        $this->expectException(EmailLoggerException::class);
        $this->expectExceptionMessage('Failed to send log message via email');

        $this->logger->send('Test message');
    }

    public function testGetTypeReturnsEmailType(): void
    {
        $this->assertEquals(LoggerType::EMAIL, $this->logger->getType());
    }

    public function testMissingRecipientEmail(): void
    {
        $this->expectException(EmailLoggerException::class);
        $this->expectExceptionMessage('Recipient email is not configured');

        new EmailLogger($this->mailerMock, '');
    }
}
