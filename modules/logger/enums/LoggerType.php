<?php

declare(strict_types=1);

namespace app\modules\logger\enums;

enum LoggerType: string
{
    case EMAIL = 'email';
    case FILE = 'file';
    case DATABASE = 'database';
}
