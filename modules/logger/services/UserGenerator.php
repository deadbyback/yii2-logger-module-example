<?php

declare(strict_types=1);

namespace app\modules\logger\services;

use app\modules\logger\factories\UserFactory;
use app\modules\logger\models\User;

final class UserGenerator
{
    public function __construct(
        private readonly UserFactory $userFactory
    ) {}

    /**
     * @return User[]
     */
    public function generate(int $count): array
    {
        $users = [];
        for ($i = 0; $i < $count; $i++) {
            $users[] = $this->userFactory->create();
        }
        return $users;
    }
}