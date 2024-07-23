<?php

namespace App\Services;

use App\Core\App;
use App\Core\Interfaces\IService;
use App\Repository\UserRepository;

class UserService implements IService
{
    public function fullList(): array
    {
        return App::getRepository(UserRepository::class)->fullList();
    }

    public function fullGet(int $id): array
    {
        return App::getRepository(UserRepository::class)->fullGet($id);
    }

    public function deleteOtherUser(int $id): bool
    {
        return App::getRepository(UserRepository::class)->delete($id);
    }

    public function updateOtherUser(int $id, array $data): bool
    {
        return App::getRepository(UserRepository::class)->userUpdate($id, $data);
    }

    public function partialList(): array
    {
        return App::getRepository(UserRepository::class)->partialList();
    }

    public function partialGet(int $id): array
    {
        return App::getRepository(UserRepository::class)->partialGet($id);
    }

    public function updateCurrentUser(array $data): bool
    {
        return App::getRepository(UserRepository::class)->update($data);
    }

    public function login(string $email, string $password): int
    {
        return App::getRepository(UserRepository::class)->login($email, $password);
    }

    public function resetPassword(): bool
    {
        return App::getRepository(UserRepository::class)->resetPassword();
    }
}
