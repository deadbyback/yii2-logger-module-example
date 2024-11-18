<?php

declare(strict_types=1);

namespace app\modules\logger\models;

use yii\base\Model;

final class User extends Model
{
    public function __construct(
        public readonly string $id,
        public readonly string $email,
        public readonly string $username,
        public readonly string $status,
        public readonly \DateTimeImmutable $createdAt
    ) {}

    public function toArray(array $fields = [], array $expand = [], $recursive = true): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'username' => $this->username,
            'status' => $this->status,
            'created_at' => $this->createdAt->format('Y-m-d H:i:s')
        ];
    }

    public function __toString(): string
    {
        return sprintf(
            'User[%s]: %s (%s) - %s',
            $this->id,
            $this->username,
            $this->email,
            $this->status
        );
    }
}