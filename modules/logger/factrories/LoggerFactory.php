<?php

namespace app\modules\logger\factories;

use app\modules\logger\interfaces\LoggerInterface;
use app\modules\logger\loggers\EmailLogger;
use app\modules\logger\loggers\FileLogger;
use app\modules\logger\loggers\DatabaseLogger;
use app\modules\logger\enums\LoggerType;
use yii\base\InvalidConfigException;

final class LoggerFactory
{
    public static function create(LoggerType $type = LoggerType::FILE): LoggerInterface
    {
        return match($type) {
            LoggerType::FILE => new FileLogger(),
            LoggerType::EMAIL => new EmailLogger(
                \Yii::$app->mailer,
                \Yii::$app->params['adminEmail']
            ),
            LoggerType::DATABASE => new DatabaseLogger(\Yii::$app->queue),
        };
    }
}