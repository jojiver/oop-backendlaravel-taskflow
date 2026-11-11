<?php

namespace App\Repositories\Contracts;

use App\Models\ApiToken;
use App\Models\User;

interface AuthRepositoryInterface
{
    public function findByEmail(string $email): ?User;

    public function createUser(array $data): User;

    public function createToken(User $user, string $tokenHash): ApiToken;

    public function findToken(string $tokenHash): ?ApiToken;

    public function deleteToken(ApiToken $token): void;
}
