<?php
session_start();

require_once '../config/Database.php';
require_once '../controllers/UserController.php';
require_once '../controllers/PlanController.php';
require_once '../controllers/ExpenseController.php';
require_once '../controllers/AdminController.php';


$action = $_GET['action'] ?? 'login';

// lista de acciones permitidas para usuarios NO logueados (Invitados)
$publicActions = ['login', 'authenticate', 'register', 'store_user'];

if (!isset($_SESSION['user_id']) && !in_array($action, $publicActions)) {
    header("Location: index.php?action=login");
    exit;
}

switch ($action) {

    // Gestion de usuarios
    case 'login':
        (new UserController())->login(); // Muestra formulario login
        break;
    case 'authenticate':
        (new UserController())->authenticate(); // Procesa el login
        break;
    case 'register':
        (new UserController())->register(); // Muestra formulario registro
        break;
    case 'store_user':
        (new UserController())->store(); // Guarda el usuario nuevo (Registro público)
        break;
    case 'logout':
        (new UserController())->logout(); // Cierra sesión
        break;


    // Gestion de planes
    case 'dashboard':
        (new PlanController())->dashboard(); // Lista todos los planes del usuario
        break;
    case 'store_plan':
        (new PlanController())->store(); // Crea un plan nuevo
        break;
    case 'view_plan':
        (new PlanController())->view(); // Ver un plan individual (show)
        break;
    case 'delete_plan':
        (new PlanController())->delete(); // Ver un plan individual (show)
        break;
    
    // Configuracion del plan (solo admins del plan)
    case 'plan_settings':
        (new PlanController())->viewSettings(); // Muestra formulario de editar plan
        break;
    case 'update_plan':
        (new PlanController())->update(); // Procesa los cambios del plan (Nombre, desc...)
        break;
    case 'store_member':
        (new PlanController())->storeMember(); // Añadir (invitar) miembro existente
        break;
    case 'remove_member':
        (new PlanController())->removeMember(); // Expulsar miembro
        break;


    // Gestion de gastos
    case 'store_expense':
        (new ExpenseController())->store(); // Crear gasto (con subida de archivo)
        break;
    case 'update_expense':
        (new ExpenseController())->update();
    case 'delete_expense':
        (new ExpenseController())->delete(); // Borrar gasto
        break;
    case 'view_receipt':
        (new ExpenseController())->viewReceipt();
    break;


    // Administración global
    case 'admin_panel':
        (new AdminController())->index(); // Dashboard general de admin
        break;
    
    case 'admin_store_user':
        (new AdminController())->storeUser(); // (FALTA) Guardar usuario creado por admin
        break;
    case 'admin_update_user':
        (new AdminController())->updateUser(); // (FALTA) Guardar edición
        break;
    case 'admin_delete_user':
        (new AdminController())->deleteUser(); // Borrar usuario
        break;

    case 'admin_delete_plan':
        (new AdminController())->deletePlan(); // Borrar plan
        break;

    default:
        echo "<h1>404 Not Found</h1><p>La acción '{$action}' no existe.</p>";
        break;
}