<?php

namespace App\Controller;

use App\Core\View;
use App\Model\Image;
use App\Model\User;

class ManagementController
{
    private $view;

    public function __construct()
    {
        $this->view = new View();
    }

    public function index()
    {
        if ($_SESSION['username']) {
            header('Location ../UserController/login');
        }

        $image = new Image();

        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        $fileToUpload = dirname(__DIR__, 2) . '/public/media/';

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            if (!empty($_FILES['filename']['name']) && $_SESSION['userID']) {

                $filename = $_FILES['filename']['name'];
                $tmp_name = $_FILES['filename']['tmp_name'];
                $user_id = $_SESSION['userID'];

                $imgFileType = strtolower(pathinfo($fileToUpload, PATHINFO_EXTENSION));
                $extensions = ["jpg", "png"];

                if (in_array($imgFileType, $extensions)) {
                    $imageBase64 = base64_encode(file_get_contents($_FILES['filename']['tmp_name']));
                    $image = 'data:image/' . $imgFileType . ';base64' . $imageBase64;
                }


                if (move_uploaded_file($_FILES['filename']['tmp_name'][0], $fileToUpload . $filename[0])) {
                    echo "File uploaded";
                }
            }
            // $filename[0]
            if (isset($filename[0])) {
                if ($image->imageUpload($user_id, $filename[0])) {
                    header('Location: ../ManagementController/index', true, 302);
                }
            }
        }

        $this->view->render('management/index', [
            'images' => $image->getAllImages(),
        ]);
    }

    public function getImages()
    {
        $image = new Image();

        $data = $image->getAllImages();

        return $data;
    }

    public function removeImage()
    {
        $image = new Image();

        if (!isset($_SESSION['username'])) {
            header('Location ../UserController/login');
        }

        $_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_URL);
        $id = isset($_GET['id']) ? $_GET['id'] : [];

        $getImage = $image->getImage($id);

        $filename = $getImage['filename'];
        $deletePath = dirname(__DIR__, 2) . '/public/media/' . $filename;

        if (unlink($deletePath)) {
            if($image->removeImage($id)) {
                header('Location: ../ManagementController/index');
                exit();
            } else {
                $this->view->render('management/index');
            }
        }
    }

    public function account()
    {
        //session_start();
        if (!$_SESSION['username']) {
            header('Location: ../UserController/login');
        }
        $user = new User();
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);


        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['email']) && isset($_POST['password'])) {

            $data = [
                'email' => $_POST['email'],
                'password' => $_POST['password']
            ];

            if ($user->changePassword($data)) {

                header('Location: ../HomeController/index', true, 301);
                exit();
            }
        }

        $this->view->render('management/account');
    }

    public function removeAcc()
    {
        $user = new User();

        $id = $_SESSION['userID'];

        if ($user->deleteAccount($id)) {

            /**
             * unset & destroy session when deleting user account
             */
            unset($_SESSION['username']);
            session_destroy();
            header('Location: ../UserController/register', true, 302);
            exit();
        } else {
            exit("Something went wrong");
        }
    }

}