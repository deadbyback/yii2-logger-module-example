<?php

declare(strict_types=1);

namespace tests\unit\modules\logger;

use app\modules\logger\enums\LoggerType;
use app\modules\logger\exceptions\FileLoggerException;
use app\modules\logger\loggers\FileLogger;

use PHPUnit\Framework\TestCase;

class FileLoggerTest extends TestCase
{
    private string $testLogFile;
    private FileLogger $logger;

    protected function setUp(): void
    {
        $this->testLogFile = sys_get_temp_dir() . '/test_' . uniqid() . '.log';
        $this->logger = new FileLogger($this->testLogFile);
    }

    protected function tearDown(): void
    {
        if (file_exists($this->testLogFile)) {
            unlink($this->testLogFile);
        }
    }

    public function testLogMessageIsWrittenToFile(): void
    {
        $message = 'Test log message';
        $this->logger->send($message);

        $this->assertFileExists($this->testLogFile);
        $logContent = file_get_contents($this->testLogFile);

        $this->assertStringContainsString($message, $logContent);
        $this->assertMatchesRegularExpression(
            '/\[\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\] Test log message/',
            $logContent
        );
    }

    public function testMultipleMessagesAreAppended(): void
    {
        $messages = ['First message', 'Second message'];

        foreach ($messages as $message) {
            $this->logger->send($message);
        }

        $logContent = file_get_contents($this->testLogFile);
        foreach ($messages as $message) {
            $this->assertStringContainsString($message, $logContent);
        }

        $this->assertEquals(2, substr_count($logContent, PHP_EOL));
    }

    public function testGetTypeReturnsFileType(): void
    {
        $this->assertEquals(LoggerType::FILE, $this->logger->getType());
    }

    public function testLogToNonWritableDirectory(): void
    {
        $this->expectException(FileLoggerException::class);
        $this->expectExceptionMessage('Directory "/nonexistent/directory" was not created');

        $logger = new FileLogger('/nonexistent/directory/test.log');
        $logger->send('Test message');
    }
}