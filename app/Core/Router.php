<?php

namespace App\Core;

use App\Controller\HomeController;

ini_set("display_errors", true);
error_reporting(E_ALL);

class Router
{
    private $controller;
    private $action;
    private $params = [];

    public function __construct()
    {
        $this->setup();
    }

    public function setup()
    {
        $url = isset($_GET['url']) ? $_GET['url'] : null;
        $url = trim($url, '/');
        $url = explode('/', $url);

        $this->controller = isset($url[0]) ? $url[0] : '/';
        $this->action = isset($url[1]) ? $url[1] : '/';

        //unset($url[0], $url[1]);

        $this->params = array_values($url);

        if (!$this->controller) {
            $default = new HomeController();
            $default->index();
        } else {
            if (file_exists(BASE_PATH . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR. 'Controller' . DIRECTORY_SEPARATOR . ucfirst($this->controller) . '.php')) {
                $controller = "\\app\\Controller\\" . ucfirst($this->controller);
                $this->controller = new $controller();

                if (method_exists($this->controller, $this->action)) {

                    if (!empty($this->params)) {
                        call_user_func_array([$this->controller, $this->action], $this->params);
                    } else {
                        $this->controller->{$this->action}();
                    }
                }
            }
        }
    }
}