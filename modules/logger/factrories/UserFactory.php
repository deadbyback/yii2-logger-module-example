<?php

declare(strict_types=1);

namespace app\modules\logger\factories;

use app\modules\logger\models\User;

final class UserFactory
{
    private const STATUSES = ['active', 'inactive', 'pending'];

    public function create(
        ?string $email = null,
        ?string $username = null,
        ?string $status = null
    ): User
    {
        return new User(
            id: uniqid('user_'),
            email: $email ?? $this->generateEmail(),
            username: $username ?? $this->generateUsername(),
            status: $status ?? $this->generateStatus(),
            createdAt: new \DateTimeImmutable()
        );
    }

    private function generateEmail(): string
    {
        return sprintf(
            '%s@example.com',
            strtolower(substr(md5(uniqid()), 0, 10))
        );
    }

    private function generateUsername(): string
    {
        return 'user_' . substr(md5(uniqid()), 0, 8);
    }

    private function generateStatus(): string
    {
        return self::STATUSES[array_rand(self::STATUSES)];
    }
}