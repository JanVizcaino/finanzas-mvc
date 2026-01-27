<?php
require_once '../config/Database.php';
require_once '../models/Expense.php';
require_once '../models/Plan.php';

class ExpenseController
{

    public function store()
    {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit;
        }

        $db = (new Database())->getConnection();
        $expenseModel = new Expense($db);

        if (isset($_POST['plan_id'], $_POST['title'], $_POST['amount'])) {
            
            $receiptPath = null;

            // LÓGICA DE SUBIDA DE FICHEROS
            if (isset($_FILES['receipt']) && $_FILES['receipt']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = '../uploads/'; // Asegúrate de que esta carpeta exista y tenga permisos
                
                // Crear nombre único para evitar sobrescribir ficheros con el mismo nombre
                $fileExtension = pathinfo($_FILES['receipt']['name'], PATHINFO_EXTENSION);
                $fileName = uniqid('receipt_') . '.' . $fileExtension;
                $targetPath = $uploadDir . $fileName;

                // Tipos permitidos (opcional, por seguridad)
                $allowedTypes = ['jpg', 'jpeg', 'png', 'pdf'];
                if (in_array(strtolower($fileExtension), $allowedTypes)) {
                    if (move_uploaded_file($_FILES['receipt']['tmp_name'], $targetPath)) {
                        // Guardamos la ruta relativa para la BBDD
                        $receiptPath = 'uploads/' . $fileName;
                    }
                }
            }

            // Llamamos al create con el nuevo parámetro
            $expenseModel->create(
                $_POST['plan_id'],
                $_SESSION['user_id'],
                $_POST['title'],
                $_POST['amount'],
                $_POST['category'],
                $receiptPath // Pasamos la ruta (o null)
            );

            header("Location: index.php?action=view_plan&id=" . $_POST['plan_id']);
            exit;
        } else {
            echo "Faltan datos para crear el gasto.";
        }
    }

    public function delete()
    {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit;
        }

        if (isset($_GET['id']) && isset($_GET['plan_id'])) {
            $db = (new Database())->getConnection();
            $expenseModel = new Expense($db);
            $planModel = new Plan($db);

            $role = $planModel->getUserRole($_GET['plan_id'], $_SESSION['user_id']);

            if ($role === 'admin') {
                $expenseModel->delete($_GET['id']);
            } else {
            }

            header("Location: index.php?action=view_plan&id=" . $_GET['plan_id']);
            exit;
        }
    }

}
