<?php

declare(strict_types=1);

namespace app\commands;

use Yii;
use yii\console\Controller;

final class QueueController extends Controller
{
    public function actionRun(): void
    {
        Yii::$app->queue->run();
    }

    public function actionListen(): void
    {
        Yii::$app->queue->run(true);
    }
}