<?php
require_once '../config/Database.php';
require_once '../models/Admin.php';
require_once '../models/User.php';

class AdminController
{
    private $db;
    private $adminModel;
    private $userModel; // 1. Añadimos esta propiedad

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->adminModel = new Admin($this->db);
        $this->userModel = new User($this->db); // 2. Instanciamos el modelo User aquí
    }

    // Middleware de seguridad
    private function checkAdminAuth() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Verificamos que sea admin
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header("Location: index.php?action=dashboard"); 
            exit;
        }
    }

    public function index()
    {
        $this->checkAdminAuth();

        // Obtenemos datos para las estadísticas y listas
        $users = $this->adminModel->getAllUsers();
        $plans = $this->adminModel->getAllPlans();

        require '../views/layout/header.php';
        require '../views/admin/dashboard.php';
        require '../views/layout/footer.php';        
    }

    // CREAR USUARIO (Desde el SlideOver)
    public function storeUser()
    {
        $this->checkAdminAuth();
        
        if (isset($_POST['username'], $_POST['email'], $_POST['password'], $_POST['role'])) {
            
            $username = trim($_POST['username']);
            $email = trim($_POST['email']);
            $password = $_POST['password'];
            $role = $_POST['role'];

            // Hashear password
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            // Usamos userModel que ya instanciamos en el constructor
            $this->userModel->create($username, $email, $passwordHash, $role);
        }
        
        header("Location: index.php?action=admin_panel");
        exit;
    }

    // ACTUALIZAR USUARIO (Corrección Importante)
    public function updateUser()
    {
        $this->checkAdminAuth();
        
        // Verificamos que lleguen los datos por POST (incluido el ID oculto)
        if (isset($_POST['id'], $_POST['username'], $_POST['email'], $_POST['role'])) {
            
            $id = $_POST['id'];
            $username = trim($_POST['username']);
            $email = trim($_POST['email']);
            $role = $_POST['role'];
            
            $passwordHash = null;

            // Lógica para la contraseña:
            // Si el campo password NO está vacío, significa que el admin quiere cambiarla.
            // Si está vacío, enviamos null para mantener la vieja.
            if (!empty($_POST['password'])) {
                $passwordHash = password_hash($_POST['password'], PASSWORD_DEFAULT);
            }

            // Llamamos a un método de actualización en el modelo User
            $this->userModel->update($id, $username, $email, $role, $passwordHash);
        }
        
        header("Location: index.php?action=admin_panel");
        exit;
    }

    // BORRAR USUARIO
    public function deleteUser()
    {
        $this->checkAdminAuth();
        
        if (isset($_GET['id'])) {
            // Validar que no se borre a sí mismo
            if ($_GET['id'] == $_SESSION['user_id']) {
                // Opcional: Podrías añadir un mensaje de error en sesión
                header("Location: index.php?action=admin_panel");
                exit;
            }

            $this->userModel->delete($_GET['id']);
        }
        
        header("Location: index.php?action=admin_panel");
        exit;
    }

    // BORRAR PLAN
    public function deletePlan()
    {
        $this->checkAdminAuth();
        
        if (isset($_GET['id'])) {
            $this->adminModel->deletePlanAsAdmin($_GET['id']);
        }
        
        header("Location: index.php?action=admin_panel");
        exit;
    }
}
?>