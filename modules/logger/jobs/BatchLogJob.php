<?php

namespace app\modules\logger\jobs;

use Throwable;
use Yii;
use yii\base\BaseObject;
use yii\queue\JobInterface;

final class BatchLogJob extends BaseObject implements JobInterface
{
    public array $messages = [];

    public function execute($queue): void
    {
        if (empty($this->messages)) {
            return;
        }

        $transaction = Yii::$app->db->beginTransaction();

        try {
            $columns = ['message', 'created_at', 'level'];
            Yii::$app->db->createCommand()
                ->batchInsert('{{%log}}', $columns, $this->messages)
                ->execute();

            $transaction->commit();
        } catch (Throwable $e) {
            $transaction->rollBack();

            foreach ($this->messages as $message) {
                try {
                    Yii::$app->db->createCommand()
                        ->insert('{{%log}}', $message)
                        ->execute();
                } catch (Throwable $e) {
                    Yii::error("Failed to save log message: {$message['message']}");
                }
            }
        }
    }
}