<?php
require_once '../config/Database.php';
require_once '../models/User.php';

class UserController
{
    public function login()
    {
        Logger::safeRun(function() {
            require '../views/layout/header.php';
            require '../views/auth/login.php';
        }, "index.php", "Load Login View");
    }

    public function authenticate()
    {
        if (!isset($_POST['username'], $_POST['password'])) {
            header("Location: index.php?action=login&error=missing_fields");
            exit;
        }

        $username = Security::clean($_POST['username']);
        $password = $_POST['password'];

        Logger::safeRun(function() use ($username, $password) {
            
            $db = (new Database())->getConnection();
            $userModel = new User($db);

            $result = $userModel->login($username, $password);

            if ($result) {
              
                session_regenerate_id(true);

                $_SESSION['user_id'] = $result['id'];
                $_SESSION['username'] = $result['username'];
                $_SESSION['role'] = $result['role'];

                header("Location: index.php?action=dashboard");
                exit;
            } else {
                
                header("Location: index.php?action=login&error=invalid_credentials");
                exit;
            }

        }, "index.php?action=login", "User Authenticate");
    }

    public function register()
    {
        Logger::safeRun(function() {
            require '../views/layout/header.php';
            require '../views/auth/register.php';
        }, "index.php", "Load Register View");
    }

    public function store()
    {
        if (!isset($_POST['username'], $_POST['password'])) {
            header("Location: index.php?action=register&error=missing_fields");
            exit;
        }

        $username = Security::clean($_POST['username']);
        $password = $_POST['password']; 

        Logger::safeRun(function() use ($username, $password) {
            
            $db = (new Database())->getConnection();
            $userModel = new User($db);


            if ($userModel->findByUsername($username)) {
                header("Location: index.php?action=register&error=user_exists");
                exit;
            }

            if ($userModel->register($username, $password)) {
                header("Location: index.php?action=login&msg=registered");
                exit;
            } else {
                throw new Exception("Fallo genérico al registrar usuario $username");
            }

        }, "index.php?action=register", "User Register Store");
    }

    public function logout()
    {
        $_SESSION = [];

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        session_destroy();

        header("Location: index.php");
        exit;
    }
}