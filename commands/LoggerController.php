<?php

namespace app\commands;

use app\modules\logger\enums\LoggerType;
use app\modules\logger\factories\LoggerFactory;
use app\modules\logger\models\LoggerType as LoggerTypeModel;
use app\modules\logger\services\UserGenerator;

use yii\console\Controller;

class LoggerController extends Controller
{
    public function __construct(
        $id,
        $module,
        private readonly UserGenerator $userGenerator,
        private readonly LoggerFactory $loggerFactory,
        $config = []
    ) {
        parent::__construct($id, $module, $config);
    }

    /**
     * Sends a log message to the default logger.
     * @return void
     */
    public function actionLog(): void
    {
        $logger = $this->loggerFactory->create();

        $users = $this->userGenerator->generate(5);
        foreach ($users as $index => $user) {
            $logger->send("LOG | New user created: $user");
        }
    }


    /**
     * Sends a log message to a special logger.
     * @param LoggerType $type Available types: file, email, database.
     * @return void
     */
    public function actionLogTo(LoggerType $type): void
    {
        $logger = $this->loggerFactory->create($type);

        $users = $this->userGenerator->generate(5);
        foreach ($users as $index => $user) {
            $logger->send("LOGTO | New user created: $user");
        }
    }

    /**
     * Sends a log message to all loggers.
     * @return void
     */
    public function actionLogToAll(): void
    {
        $loggerInstances = [];
        foreach (LoggerTypeModel::getAll() as $type) {
            $loggerInstances[] = $this->loggerFactory->create($type);
        }

        $users = $this->userGenerator->generate(5);
        foreach ($users as $index => $user) {
            foreach ($loggerInstances as $loggerInstance) {
                $loggerInstance->send("LOGTOALL | New user created: $user");
            }
        }
    }
}