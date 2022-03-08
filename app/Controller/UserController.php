<?php

namespace App\Controller;

use App\Core\DB;
use App\Core\View;
use App\Model\User;

class UserController
{
    private $view;

    public function __construct()
    {
        $this->view = new View();
    }

    public function register()
    {

        $user = new User();

        $data = [];

        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

        if ($_SERVER['REQUEST_METHOD']  == 'POST') {
            $data['firstname'] = trim($_POST['firstname']);
            $data['lastname'] = trim($_POST['lastname']);
            $data['username'] = trim($_POST['username']);
            $data['email'] = trim($_POST['email']);
            $data['password'] = trim($_POST['password']);
            $data['repeat-password'] = trim($_POST['repeat-password']);

            $data =[
                'firstname' => trim($_POST['firstname']),
                'lastname' => trim($_POST['lastname']),
                'username' => trim($_POST['username']),
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'repeat-password' => trim($_POST['repeat-password']),
                'firstname_err' => '',
                'lastname_err' => '',
                'username_err' => '',
                'email_err' => '',
                'password_err' => '',
                'repeat-password_err' => ''
            ];

            if(empty($data['firstname'])){
                echo "<script>alert('Enter your first name')";
                exit();
            }


            if(empty($data['lastname'])){
                $data['lastname_err'] = 'Enter last name';
            }

            if (empty($data['username'])) {
                $data['username_err'] = 'Enter your username';
            }

            if(empty($data['email'])){
                $data['email_err'] = 'Enter your email';
            }

            if(empty($data['password'])){
                $data['password_err'] = 'Enter your password';
            } elseif(strlen($data['password']) < 6){
                $data['password_err'] = 'Password must be at least 6 characters';
            }

            if(empty($data['repeat-password'])){
                $data['repeat-password_err'] = 'Confirm your password';
            } else {
                if($data['password'] != $data['repeat-password']){
                    $data['repeat_password_err'] = 'No match found! Try again!';
                }
            }

            if(empty($data['firstname_err']) && empty($data['lastname_err'])
                && empty($data['username_err'])
                && empty($data['email_err'])
                && empty($data['password_err'])
                && empty($data['repeat-password_err-password_err'])){

                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

                if ($user->register($data)) {

                    header('Location: ../UserController/login', true, 301);
                } else {
                    exit("Your registration attempt failed");
                }
            } else {
                $this->view->render('user/register', $data);
            }
        } else {
            $data = [
                'firstname' => '',
                'lastname' => '',
                'username' => '',
                'email' => '',
                'password' => '',
                'repeat-password' => '',
                'firstname_err' => '',
                'lastname_err' => '',
                'username_err' => '',
                'email_err' => '',
                'password_err' => '',
                'password-repeat_err' => '',
            ];
        }

        $this->view->render('user/register', $data);
    }

    public function login()
    {
        //session_start();
        $user = new User();

        //$this->view->render('user/login');
        $day = 30;
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];

            setcookie('remember_user', $_POST["username"], time() + 60 * 60 * 24 * $day);

            if ($user->login($username, $email, $password)) {

                $id = $user->getId();
                $username = $user->getUsername();

                $_SESSION['userID'] = $id;
                $_SESSION['username']  = $username;
                $_SESSION['isLoggedIn'] = true;

                header('Location: ../HomeController/index', true, 301);
            } else {
                "<script>alert('Login failed'); </script>";
                $this->view->render('user/login');
            }
        } else {
            $this->view->render('user/login');
        }
    }

    public function logout()
    {

        if (isset($_SESSION['username']) && isset($_SESSION['userID']) && isset($_SESSION['isLoggedIn'])) {
            session_unset(); // deletes variables from session
            //unset($_SESSION['username']);
            session_destroy(); // destroys all the data associated with the current session

            header('Location: ../HomeController/index', true, 301);
            exit();
        }
    }
}