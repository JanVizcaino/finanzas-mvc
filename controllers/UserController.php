<?php
require_once '../config/Database.php';
require_once '../models/User.php';

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

        if (isset($_POST['username']) && isset($_POST['password'])) {
            $result = $user->login($_POST['username'], $_POST['password']);

            if ($result) {
                $_SESSION['user_id'] = $result['id'];
                $_SESSION['username'] = $result['username'];
                $_SESSION['role'] = $result['role'];
                header("Location: index.php?action=dashboard");
                exit;
            }
        }

        header("Location: index.php?action=login&error=1");
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

        if ($user->register($_POST['username'], $_POST['password'])) {
            header("Location: index.php?action=login");
        } else {
            echo "Error registrando usuario (quizás el nombre ya existe).";
        }
    }

    public function logout()
    {
        session_destroy();
        header("Location: index.php");
    }
}
