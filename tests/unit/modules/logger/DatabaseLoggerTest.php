<?php

namespace app\tests\unit\modules\logger;

use app\modules\logger\loggers\DatabaseLogger;
use app\modules\logger\models\Log;
use app\modules\logger\enums\LoggerType;
use app\modules\logger\exceptions\DatabaseLoggerException;
use PHPUnit\Framework\TestCase;
use Yii;

class DatabaseLoggerTest extends TestCase
{
    private DatabaseLogger $logger;

    protected function setUp(): void
    {
        $this->logger = new DatabaseLogger(\Yii::$app->queue);

        Log::deleteAll();
    }

    public function testSuccessfulLogSave(): void
    {
        $message = 'Test log message';
        $this->logger->send($message);

        $log = Log::find()->orderBy(['id' => SORT_DESC])->one();

        $this->assertNotNull($log);
        $this->assertEquals($message, $log->message);
        $this->assertEquals('info', $log->level);
        $this->assertNotNull($log->created_at);
    }

    public function testMultipleLogMessages(): void
    {
        $messages = ['First message', 'Second message'];

        foreach ($messages as $message) {
            $this->logger->send($message);
        }

        $logs = Log::find()->orderBy(['id' => SORT_ASC])->all();

        $this->assertCount(2, $logs);
        foreach ($logs as $index => $log) {
            $this->assertEquals($messages[$index], $log->message);
        }
    }

    public function testGetTypeReturnsDatabaseType(): void
    {
        $this->assertEquals(LoggerType::DATABASE, $this->logger->getType());
    }

    public function testInvalidMessageThrowsException(): void
    {
        $this->expectException(DatabaseLoggerException::class);

        $this->logger->send('');
    }
}