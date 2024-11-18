<?php

namespace app\modules\logger\models;

use app\modules\logger\enums\LoggerType as LoggerTypeEnum;

class LoggerType
{
    public static function getAll(): array
    {
        return [
            LoggerTypeEnum::FILE,
            LoggerTypeEnum::EMAIL,
            LoggerTypeEnum::DATABASE,
        ];
    }
}