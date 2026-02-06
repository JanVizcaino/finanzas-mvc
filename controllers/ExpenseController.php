<?php

require_once '../config/Database.php';
require_once '../models/Expense.php';
require_once '../models/Plan.php';
require_once '../models/User.php'; 

class ExpenseController
{
    public function store()
    {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit;
        }

        if (!isset($_POST['plan_id'], $_POST['title'], $_POST['amount'])) {
            header("Location: index.php?action=dashboard&error=missing_data");
            exit;
        }

        $planId   = Security::cleanInt($_POST['plan_id']);
        $title    = Security::clean($_POST['title']);
        $amount   = Security::cleanMoney($_POST['amount']);
        $category = Security::clean($_POST['category']);
        $detail   = Security::clean($_POST['detail']);
        $userId   = $_SESSION['user_id'];

        Logger::safeRun(function() use ($planId, $userId, $title, $amount, $category, $detail) {
            
            $db = (new Database())->getConnection();
            $expenseModel = new Expense($db);
            $planModel = new Plan($db);
            $userModel = new User($db);

            $receiptPath = null;
            
            if (isset($_FILES['receipt']) && $_FILES['receipt']['error'] === UPLOAD_ERR_OK) {
                
                $finfo = new finfo(FILEINFO_MIME_TYPE);
                $mimeType = $finfo->file($_FILES['receipt']['tmp_name']);
                
                if (in_array($mimeType, Config::ALLOWED_MIME_TYPES)) {
                    $ext = pathinfo($_FILES['receipt']['name'], PATHINFO_EXTENSION);
                    $fileName = uniqid('receipt_') . '.' . $ext;
                    $targetPath = Config::UPLOAD_DIR . $fileName;

                    if (move_uploaded_file($_FILES['receipt']['tmp_name'], $targetPath)) {
                        $receiptPath = 'uploads/' . $fileName;
                    }
                } else {
                    Logger::log("Security Alert: Intento de subir archivo no permitido ($mimeType) por usuario $userId", "Upload Security");
                }
            }

            $result = $expenseModel->create($planId, $userId, $title, $amount, $category, $receiptPath, $detail);

            if (!$result) {
                throw new Exception("Error al insertar gasto en la base de datos.");
            }

            $recipients = $planModel->getSubscribers($planId);
            if (!empty($recipients)) {
                $creator = $userModel->getById($userId);
                $amountFormatted = number_format($amount, 2) . "€";

                $baseUrl = Config::getAppUrl();
                $baseUrl = rtrim($baseUrl, '/');
                $planLink = $baseUrl . "/index.php?action=view_plan&id=" . $planId;
                
                foreach ($recipients as $emailTo) {
                    $payload = [
                        "type" => "Email",
                        "event" => "new_expense",
                        "recipient_email" => $emailTo,
                        "template_title" => "Nuevo Gasto: " . $title,
                        "template_description" => "Hola, <b>{$creator['username']}</b> acaba de registrar un gasto de <b>{$amountFormatted}</b> en la categoría {$category}.",
                        "template_link" => $planLink,
                        "raw_data" => [
                            "plan_id" => $planId,
                            "amount" => $amount,
                            "creator" => $creator['username']
                        ]
                    ];

                    Webhook::send($payload);
                }
            }

            header("Location: index.php?action=view_plan&id=" . $planId . "&msg=expense_created");
            exit;

        }, "index.php?action=view_plan&id=" . $planId, "Store Expense");
    }

    public function update()
    {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit;
        }

        if (!isset($_POST['id'], $_POST['plan_id'])) {
            header("Location: index.php?action=dashboard");
            exit;
        }

        $id       = Security::cleanInt($_POST['id']);
        $planId   = Security::cleanInt($_POST['plan_id']);
        $title    = Security::clean($_POST['title']);
        $amount   = Security::cleanMoney($_POST['amount']);
        $category = Security::clean($_POST['category']);
        $detail   = Security::clean($_POST['detail']);
        $userId   = $_SESSION['user_id'];

        Logger::safeRun(function() use ($id, $planId, $userId, $title, $amount, $category, $detail) {

            $db = (new Database())->getConnection();
            $expenseModel = new Expense($db);
            $planModel = new Plan($db);

            $currentExpense = $expenseModel->getById($id);
            if (!$currentExpense) throw new Exception("Gasto no encontrado ID: $id");

            $planRole = $planModel->getUserRole($planId, $userId);

            if ($planRole !== 'admin' && $currentExpense['user_id'] != $userId) {
                throw new Exception("Intento de edición no autorizada por usuario $userId en gasto $id");
            }

            $receiptPath = $currentExpense['receipt_path'];

            if (isset($_FILES['receipt']) && $_FILES['receipt']['error'] === UPLOAD_ERR_OK) {
                $finfo = new finfo(FILEINFO_MIME_TYPE);
                $mimeType = $finfo->file($_FILES['receipt']['tmp_name']);

                if (in_array($mimeType, Config::ALLOWED_MIME_TYPES)) {
                    $ext = pathinfo($_FILES['receipt']['name'], PATHINFO_EXTENSION);
                    $fileName = uniqid('receipt_') . '.' . $ext;
                    $targetPath = Config::UPLOAD_DIR . $fileName;

                    if (move_uploaded_file($_FILES['receipt']['tmp_name'], $targetPath)) {
                        if (!empty($receiptPath) && file_exists('../' . $receiptPath)) {
                            unlink('../' . $receiptPath);
                        }
                        $receiptPath = 'uploads/' . $fileName;
                    }
                }else {
                    Logger::log("Security Alert: Intento de subir archivo no permitido ($mimeType) por usuario $userId", "Upload Security");
                }
            }

            $expenseModel->update($id, $title, $amount, $category, $receiptPath, $detail);
            
            header("Location: index.php?action=view_plan&id=" . $planId . "&msg=expense_updated");
            exit;

        }, "index.php?action=view_plan&id=" . $planId, "Update Expense");
    }

    public function delete()
    {
        if (!isset($_SESSION['user_id'])) header("Location: index.php?action=login");

        if (isset($_GET['id'], $_GET['plan_id'])) {
            
            $expenseId = Security::cleanInt($_GET['id']);
            $planId    = Security::cleanInt($_GET['plan_id']);
            $userId    = $_SESSION['user_id'];

            Logger::safeRun(function() use ($expenseId, $planId, $userId) {
                
                $db = (new Database())->getConnection();
                $expenseModel = new Expense($db);
                $planModel = new Plan($db);

                $expense = $expenseModel->getById($expenseId);

                if ($expense) {
                    $planRole = $planModel->getUserRole($planId, $userId);

                    if ($planRole === 'admin' || $expense['user_id'] == $userId) {
                        if (!empty($expense['receipt_path']) && file_exists('../' . $expense['receipt_path'])) {
                            unlink('../' . $expense['receipt_path']);
                        }
                        $expenseModel->delete($expenseId);
                    } else {
                        throw new Exception("Permiso denegado para borrar gasto $expenseId");
                    }
                }
                
                header("Location: index.php?action=view_plan&id=" . $planId . "&msg=deleted");
                exit;

            }, "index.php?action=view_plan&id=" . $planId, "Delete Expense");
        }
    }

    public function viewReceipt()
    {
        if (!isset($_SESSION['user_id'])) die("Acceso denegado.");

        if (isset($_GET['id'])) {
            
            $id = Security::cleanInt($_GET['id']);
            $userId = $_SESSION['user_id'];

            Logger::safeRun(function() use ($id, $userId) {
                
                $db = (new Database())->getConnection();
                $expenseModel = new Expense($db);
                $planModel = new Plan($db);

                $expense = $expenseModel->getById($id);
                if (!$expense) throw new Exception("Gasto no encontrado para recibo");

                $role = $planModel->getUserRole($expense['plan_id'], $userId);
                if (!$role) throw new Exception("Acceso denegado al recibo del gasto $id");

                $filePath = '../' . $expense['receipt_path'];
                
                if (file_exists($filePath)) {
                    $mimeType = mime_content_type($filePath);
                    header('Content-Type: ' . $mimeType);
                    header('Content-Disposition: inline; filename="recibo_' . $id . '"');
                    header('Content-Length: ' . filesize($filePath));
                    readfile($filePath);
                    exit;
                } else {
                    die("Archivo no encontrado en el servidor.");
                }

            }, "index.php?action=dashboard", "View Receipt");
        }
    }
}