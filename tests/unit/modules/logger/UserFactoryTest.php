<?php

namespace app\tests\unit\modules\logger;

use app\modules\logger\factories\UserFactory;

use PHPUnit\Framework\TestCase;

class UserFactoryTest extends TestCase
{
    private UserFactory $factory;

    protected function setUp(): void
    {
        $this->factory = new UserFactory();
    }

    public function testCreatesUserWithCustomData(): void
    {
        $email = 'test@example.com';
        $username = 'testuser';
        $status = 'active';

        $user = $this->factory->create($email, $username, $status);

        $this->assertEquals($email, $user->email);
        $this->assertEquals($username, $user->username);
        $this->assertEquals($status, $user->status);
    }

    public function testCreatesUserWithGeneratedData(): void
    {
        $user = $this->factory->create();

        $this->assertNotEmpty($user->id);
        $this->assertNotEmpty($user->email);
        $this->assertNotEmpty($user->username);
        $this->assertMatchesRegularExpression('/^(active|inactive|pending)$/', $user->status);
    }
}