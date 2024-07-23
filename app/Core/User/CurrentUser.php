<?php

namespace App\Core\User;

use App\Core\Cache;
use App\Core\SessionManager;

class CurrentUser
{
    private static $instance;
    private User $user;

    private function __construct()
    {
        $sessionManager = SessionManager::getInstance();
        $userKey = $sessionManager->get('user_key');
        if ($userKey) {
            $this->user = Cache::getUserFromCache($userKey);
        }
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
        $userKey = bin2hex(random_bytes(15));
        SessionManager::getInstance()->set('user_key', $userKey);
        Cache::saveUserToCache($userKey, $user);
    }

    public function getUser(): User
    {
        return $this->user;
    }
}
