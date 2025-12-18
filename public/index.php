<?php
session_start(); 

require_once '../config/Database.php';
require_once '../controllers/ExpenseController.php'; 
require_once '../controllers/UserController.php';
require_once '../controllers/PlanController.php';

$action = $_GET['action'] ?? 'login';

if (!isset($_SESSION['user_id']) && !in_array($action, ['login', 'authenticate', 'register', 'store_user'])) {
    header("Location: index.php?action=login");
    exit;
}

switch ($action) {
    // Auth
    case 'login':
        (new UserController())->login();
        break;
    case 'authenticate':
        (new UserController())->authenticate();
        break;
    case 'register':
        (new UserController())->register();
        break;
    case 'store_user':
        (new UserController())->store();
        break;
    case 'logout':
        (new UserController())->logout();
        break;

    // Planes
    case 'dashboard':
        (new PlanController())->dashboard();
        break;
    case 'store_plan':
        (new PlanController())->store();
        break;
    case 'view_plan':
        (new PlanController())->view();
        break;

    // Acciones dentro del plan
    case 'store_member':
        (new UserController())->storeMember();
        break; // Crear usuario desde el plan
    case 'store_expense':
        (new ExpenseController())->store();
        break;
    case 'delete_expense':
        (new ExpenseController())->delete();
        break;
    case 'remove_member':
        (new UserController())->removeMember();
        break;
    default:
        echo "404 Not Found";
        break;
}
