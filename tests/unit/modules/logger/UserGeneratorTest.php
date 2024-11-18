<?php

namespace app\tests\unit\modules\logger;

use app\modules\logger\factories\UserFactory;
use app\modules\logger\services\UserGenerator;
use app\modules\logger\models\User;

use PHPUnit\Framework\TestCase;

class UserGeneratorTest extends TestCase
{
    private UserGenerator $generator;

    protected function setUp(): void
    {
        $this->generator = new UserGenerator(new UserFactory());
    }

    public function testGeneratesRequestedNumberOfUsers(): void
    {
        $users = $this->generator->generate(5);

        $this->assertCount(5, $users);
        foreach ($users as $user) {
            $this->assertInstanceOf(User::class, $user);
        }
    }

    public function testGeneratedUsersHaveUniqueIds(): void
    {
        $users = $this->generator->generate(10);
        $ids = array_map(fn(User $user) => $user->id, $users);

        $this->assertCount(10, array_unique($ids));
    }
}