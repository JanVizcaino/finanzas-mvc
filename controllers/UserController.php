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

    public function storeMember()
    {
        $db = (new Database())->getConnection();
        $user = new User($db);
        $plan = new Plan($db);

        $planId = $_POST['plan_id'];
        $email = $_POST['email'];

        $role = $plan->getUserRole($planId, $_SESSION['user_id']);
        if ($role !== 'admin') die("No tienes permisos para añadir gente.");

        $existingUser = $user->findByEmail($email);

        if ($existingUser) {
            $targetUserId = $existingUser['id'];

            try {
                $plan->addMember($planId, $targetUserId, 'member');
            } catch (Exception $e) {
            }

            header("Location: index.php?action=view_plan&id=" . $planId);
        } else {
            die("Error: El usuario con el email <b>$email</b> no está registrado en el sistema. Pídele que se registre antes de añadirlo.");
        }
    }

    public function removeMember()
    {
        $db = (new Database())->getConnection();
        $plan = new Plan($db);

        $planId = $_GET['plan_id'];
        $targetUserId = $_GET['user_id'];

        $role = $plan->getUserRole($planId, $_SESSION['user_id']);
        if ($role !== 'admin') die("No tienes permisos.");

        if ($targetUserId == $_SESSION['user_id']) die("No puedes borrarte a ti mismo si eres admin.");

        $plan->removeMember($planId, $targetUserId);
        header("Location: index.php?action=view_plan&id=" . $planId);
    }
}
