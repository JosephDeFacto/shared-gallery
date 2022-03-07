<?php

namespace App\Controller;

use App\Core\View;
use App\Model\Image;

ini_set("display_errors", true);
error_reporting(E_ALL);

class HomeController
{
    private $view;

    public function __construct()
    {
        $this->view = new View();
    }

   public function index()
    {

        $this->view->render('home/index');
    }

    public function numberOfImages()
    {
        //session_start();
        $image = new Image();


        $data['number'] = $image->getNumberOfImages();
        echo $data['number'];
    }
}
