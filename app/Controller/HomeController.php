<?php

namespace App\Controller;

use App\Core\View;
use App\Model\Image;


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
        $image = new Image();


        $data['number'] = $image->getNumberOfImages();
        echo $data['number'];
    }
}
