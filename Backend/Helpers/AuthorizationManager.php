<?php

namespace CCS\Helpers;

class AuthorizationManager {
    public static function authorize(array $authority): bool {
        $user = $_SESSION['user'];
        return in_array($user->{'userRole'}, $authority);
    }

    public static function authenticate() {
        return $_SESSION['user'] ?? null;
    }

    public static function login($user) {
        $_SESSION['user'] = $user;
    }

    public static function logout() {
        session_unset();
        session_destroy();
        session_write_close();
    }
}
