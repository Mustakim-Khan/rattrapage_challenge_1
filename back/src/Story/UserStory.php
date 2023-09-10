<?php

namespace App\Story;

use Zenstruck\Foundry\Story;
use App\Factory\UserFactory;

final class UserStory extends Story
{
    public function build(): void
    {
        UserFactory::createOne([
            'email' => 'user1@gmail.com',
            'roles' => ['ROLE_USER'],
            'username' => 'user1',
        ]);

        UserFactory::createOne([
            'email' => 'user2@gmail.com',
            'roles' => ['ROLE_USER'],
            'username' => 'user2',
        ]);

        UserFactory::createOne([
            'email' => 'user3@gmail.com',
            'roles' => ['ROLE_USER'],
            'username' => 'user3',
        ]);

        UserFactory::createOne([
            'email' => 'admin1@gmail.com',
            'roles' => ['ROLE_ADMIN'],
            'username' => 'admin1',
        ]);

        UserFactory::createOne([
            'email' => 'admin2@gmail.com',
            'roles' => ['ROLE_ADMIN'],
            'username' => 'admin2',
        ]);

        UserFactory::createMany(5);
    }
}
