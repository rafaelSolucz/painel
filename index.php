<?php
declare(strict_types=1);

namespace App;

session_start();

use App\Controller\AuthController;
use App\Controller\DashboardController;
use App\Controller\UsuarioController; // Conferir de que esta linha existe
use App\Controller\AdminDashboardController;

require_once("./autoload.php");
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Solucz</title>
    <link rel="stylesheet" href="./assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="shortcut icon" href="assets\img\favicon.ico" type="image/x-icon">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
    <?php
    $page = $_GET["p"] ?? "login";
    $action = $_GET["a"] ?? null;

    if ($page !== 'login' && $page !== 'logout') {
        AuthController::requireLogin();
    }

    match ($page) {
        "login" => AuthController::login(),
        "logout" => AuthController::logout(),
        "dashboards" => DashboardController::displayDashboards(), // Adicionei esta linha
        "admin_usuarios" => UsuarioController::manageUsers($action),
        "admin_dashboards" => AdminDashboardController::manageDashboards($action),
        default => require_once("./view/404.php"),
    };
    ?>
</body>
</html>