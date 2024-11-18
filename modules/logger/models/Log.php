<?php

declare(strict_types=1);

namespace app\modules\logger\models;

use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property string $message
 * @property string $created_at
 * @property string $level
 */
final class Log extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%log}}';
    }

    public function rules(): array
    {
        return [
            [['message'], 'required'],
            [['message'], 'string'],
            [['level'], 'string', 'max' => 32],
            [['level'], 'default', 'value' => 'info'],
        ];
    }
}