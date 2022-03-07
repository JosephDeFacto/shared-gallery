<?php

namespace App\Controller;

use App\Core\View;
use App\Model\Image;

ini_set("display_errors", true);
error_reporting(E_ALL);

class AjaxController
{
    private View $view;

    public function __construct()
    {
        $this->view = new View();
    }

    public function index()
    {
        session_start();
        $image = new Image();

        $id = $_SESSION['userID'] ?? [];

        $data = [];
        $data['number'] = $image->getNumberOfImages($id);
        echo $data['number'];
        $this->view->render('home/ajax');
    }
}
