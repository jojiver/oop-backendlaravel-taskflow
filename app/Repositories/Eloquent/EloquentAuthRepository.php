<?php

namespace App\Repositories\Eloquent;

use App\Models\ApiToken;
use App\Models\User;
use App\Repositories\Contracts\AuthRepositoryInterface;

class EloquentAuthRepository implements AuthRepositoryInterface
{
    public function findByEmail(string $email): ?User
    {
        return User::query()->where('email', $email)->first();
    }

    public function createUser(array $data): User
    {
        return User::query()->create($data);
    }

    public function createToken(User $user, string $tokenHash): ApiToken
    {
        return $user->apiTokens()->create([
            'token_hash' => $tokenHash,
        ]);
    }

    public function findToken(string $tokenHash): ?ApiToken
    {
        return ApiToken::query()
            ->with('user')
            ->where('token_hash', $tokenHash)
            ->first();
    }

    public function deleteToken(ApiToken $token): void
    {
        $token->delete();
    }
}
