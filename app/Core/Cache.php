<?php

namespace App\Core;

use App\Core\User\User;

class Cache
{
    public static function saveUserToCache(string $key, User $user): void
    {
        $cacheDir = $_ENV["DIR"] . "/" . 'user_cache/';
        if (!is_dir($cacheDir)) {
            mkdir($cacheDir, 0755, true);
        }
        $filePath = $cacheDir . $key . '.cache';
        $data = [
            'user' => serialize($user),
            'expires' => time() + 86400 // 1 сутки
        ];
        file_put_contents($filePath, serialize($data));
    }

    public static function getUserFromCache(string $key)
    {
        $cacheDir = $_ENV["DIR"] . "/" . 'user_cache/';
        $filePath = $cacheDir . $key . '.cache';
        if (file_exists($filePath)) {
            $data = unserialize(file_get_contents($filePath));
            if ($data['expires'] > time()) {
                return unserialize($data['user']);
            } else {
                unlink($filePath); // удаляем устаревший кэш
            }
        }
        return null;
    }
}
