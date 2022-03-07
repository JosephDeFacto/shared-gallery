<?php

namespace App\Core;

class Config
{
    public static function config($key)
    {
        $config = include BASE_PATH . DIRECTORY_SEPARATOR . "app" . DIRECTORY_SEPARATOR . "config.php";

        return $config[$key];
    }
}