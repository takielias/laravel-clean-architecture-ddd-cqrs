<?php

declare(strict_types=1);

namespace Infrastructure\Repositories;

use Domain\Entities\User;
use Domain\Repositories\UserRepositoryContract;

class UserRepository implements UserRepositoryContract
{
    public function save(string $name, string $email, string $password): string
    {
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => $password,
        ]);

        return $user->email;
    }

    public function findByEmail(string $email): ?User
    {
        $user = User::where('email', $email)->first();

        return $user ?? null;
    }
}
