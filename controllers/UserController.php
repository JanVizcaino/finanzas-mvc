<?php
require_once '../config/Database.php';
require_once '../models/User.php';
require_once '../models/Plan.php';

class UserController {
    
    // 1. Mostrar Login
    public function login() {
        require '../views/layout/header.php';
        require '../views/auth/login.php';
        require '../views/layout/footer.php';
    }

    // 2. Procesar Login
    public function authenticate() {
        $db = (new Database())->getConnection();
        $user = new User($db);
        $result = $user->login($_POST['email'], $_POST['password']);

        if ($result) {
            $_SESSION['user_id'] = $result['id'];
            $_SESSION['username'] = $result['username'];
            header("Location: index.php?action=dashboard");
        } else {
            echo "Credenciales incorrectas";
        }
    }

    // 3. Registro normal (Vista)
    public function register() {
        require '../views/layout/header.php';
        require '../views/auth/register.php';
        require '../views/layout/footer.php';
    }

    // 4. Guardar usuario (Registro normal)
    public function store() {
        $db = (new Database())->getConnection();
        $user = new User($db);
        if ($user->register($_POST['username'], $_POST['email'], $_POST['password'])) {
            header("Location: index.php?action=login");
        } else {
            echo "Error registrando.";
        }
    }

    // 5. Cerrar Sesión
    public function logout() {
        session_destroy();
        header("Location: index.php");
    }

    // 6. Crear usuario DENTRO de un plan (Solo Admin del plan)
public function storeMember() {
        $db = (new Database())->getConnection();
        $user = new User($db);
        $plan = new Plan($db);
        
        $planId = $_POST['plan_id'];
        $email = $_POST['email'];
        $username = $_POST['username'];
        $password = $_POST['password'];

        // 1. SEGURIDAD: Verificar que soy admin
        $role = $plan->getUserRole($planId, $_SESSION['user_id']);
        if ($role !== 'admin') die("No tienes permisos para añadir gente.");

        // 2. LÓGICA INTELIGENTE:
        $targetUserId = null;
        
        // A) Comprobamos si el usuario ya existe en la BD
        $existingUser = $user->findByEmail($email);

        if ($existingUser) {
            // Si existe, usamos su ID
            $targetUserId = $existingUser['id'];
        } else {
            // Si no existe, lo creamos
            $targetUserId = $user->register($username, $email, $password);
        }
        
        // 3. AÑADIR AL PLAN
        if ($targetUserId) {
            // Usamos try-catch por si el usuario YA estaba en el plan (evita otro error SQL)
            try {
                $plan->addMember($planId, $targetUserId, 'member');
            } catch (Exception $e) {
                // Si falla es porque ya estaba en el plan, no hacemos nada y redirigimos igual
            }
            
            header("Location: index.php?action=view_plan&id=" . $planId);
        } else {
            echo "Error: No se pudo gestionar el usuario.";
        }
    }

    // 7. Eliminar miembro del plan (Solo Admin del plan)
    public function removeMember() {
        $db = (new Database())->getConnection();
        $plan = new Plan($db);
        
        $planId = $_GET['plan_id'];
        $targetUserId = $_GET['user_id'];

        // SEGURIDAD
        $role = $plan->getUserRole($planId, $_SESSION['user_id']);
        if ($role !== 'admin') die("No tienes permisos.");

        // Evitar borrarse a uno mismo
        if ($targetUserId == $_SESSION['user_id']) die("No puedes borrarte a ti mismo si eres admin.");

        $plan->removeMember($planId, $targetUserId);
        header("Location: index.php?action=view_plan&id=" . $planId);
    }

} // Fin de la clase UserController
?>