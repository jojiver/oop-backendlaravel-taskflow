<?php

namespace App\Services;

use App\Models\ApiToken;
use App\Models\User;
use App\Repositories\Contracts\AuthRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthService
{
    public function __construct(
        private readonly AuthRepositoryInterface $auth,
    ) {}

    public function register(array $data): array
    {
        $user = $this->auth->createUser($data);

        return [$user, $this->issueToken($user)];
    }

    public function login(string $email, string $password): ?array
    {
        $user = $this->auth->findByEmail($email);

        if (! $user || ! Hash::check($password, $user->password)) {
            return null;
        }

        return [$user, $this->issueToken($user)];
    }

    public function logout(ApiToken $token): void
    {
        $this->auth->deleteToken($token);
    }

    public function authenticate(string $plainToken): ?array
    {
        $token = $this->auth->findToken(hash('sha256', $plainToken));

        if (! $token || ! $token->user) {
            return null;
        }

        return [$token, $token->user];
    }

    private function issueToken(User $user): string
    {
        $plainToken = Str::random(60);

        $this->auth->createToken($user, hash('sha256', $plainToken));

        return $plainToken;
    }
}
