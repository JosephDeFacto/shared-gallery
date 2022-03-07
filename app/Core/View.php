<?php

namespace App\Core;

ini_set("display_errors", 1);
error_reporting(E_ALL);

class View
{

    public function render($name, $data = [])
    {
        $path = BASE_PATH . DIRECTORY_SEPARATOR . "app" . DIRECTORY_SEPARATOR . "views" . DIRECTORY_SEPARATOR . "$name.phtml";
        extract($data);
        if (file_exists($path)) {
            require_once $path;
        } else {
            die("Does Not Exists");
        }
    }
}