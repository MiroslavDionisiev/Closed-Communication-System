<?php

namespace CCS\Helpers;

class AuthorizationManager {
    public static function authorize(array $authority): bool {
        $user = $_SESSION['user'];
        return in_array($user->{'userRole'}, $authority);
    }

    public static function authenticate() {
        return isset($_SESSION['user']);
    }
}

?>
