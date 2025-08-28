<?php
namespace App\Controller;

use App\Dal\DashboardDao;
use App\View\DashboardView;

abstract class DashboardController
{
    public static function displayDashboards(): void
    {
        AuthController::requireLogin();
        $userRole = $_SESSION['user_role'] ?? 'control';

        try {
            $dashboards = DashboardDao::listarPorRole($userRole);
            DashboardView::displayDashboards($dashboards);
        } catch (\PDOException $e) {
            // Em caso de erro, você pode exibir uma mensagem de erro ou redirecionar.
            // Por enquanto, vamos passar um array vazio para a view.
            DashboardView::displayDashboards([]);
            error_log("Erro ao listar dashboards: " . $e->getMessage());
        }
    }
}