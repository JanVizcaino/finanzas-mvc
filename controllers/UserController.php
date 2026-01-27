<?php
require_once '../config/Database.php';
require_once '../models/User.php';
require_once '../models/Plan.php';

class UserController
{

    public function login()
    {
        require '../views/layout/header.php';
        require '../views/auth/login.php';
    }

    public function authenticate()
    {
        $db = (new Database())->getConnection();
        $user = new User($db);
        $result = $user->login($_POST['email'], $_POST['password']);

        if ($result) {
            $_SESSION['user_id'] = $result['id'];
            $_SESSION['username'] = $result['username'];
            $_SESSION['role'] = $result['role'];
            header("Location: index.php?action=dashboard");
        } else {
            echo "Credenciales incorrectas";
        }
    }

    public function register()
    {
        require '../views/layout/header.php';
        require '../views/auth/register.php';
    }

    public function store()
    {
        $db = (new Database())->getConnection();
        $user = new User($db);
        if ($user->register($_POST['username'], $_POST['email'], $_POST['password'])) {
            header("Location: index.php?action=login");
        } else {
            echo "Error registrando.";
        }
    }

    public function logout()
    {
        session_destroy();
        header("Location: index.php");
    }


}
