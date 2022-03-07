<?php

namespace App\Core;

class Session
{

    private static Session $instance;

    private function __construct()
    {
        session_start();
    }

    public static function getInstance(): Session
    {
        if (self::$instance == null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public static function login()
    {
        $_SESSION['isLoggedIn'] = true;
    }

    public static function logout()
    {
        session_destroy();
        unset($_SESSION['isLogged']);
    }

    public static function isLoggedIn(): bool
    {
        return isset($_SESSION['isLoggedIn']);
    }
}