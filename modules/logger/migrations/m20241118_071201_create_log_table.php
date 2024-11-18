<?php

declare(strict_types=1);

namespace app\modules\logger\migrations;

use yii\db\Migration;

final class m20241118_071201_create_log_table extends Migration
{
    public function safeUp(): void
    {
        $this->createTable('{{%log}}', [
            'id' => $this->primaryKey(),
            'message' => $this->text()->notNull(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'level' => $this->string(32)->notNull()->defaultValue('info'),
        ]);

        $this->createIndex('idx-log-created_at', '{{%log}}', 'created_at');
        $this->createIndex('idx-log-level', '{{%log}}', 'level');
    }

    public function safeDown(): void
    {
        $this->dropTable('{{%log}}');
    }
}