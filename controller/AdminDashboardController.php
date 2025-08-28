<?php
// controller/AdminDashboardController.php

namespace App\Controller;

use App\Dal\DashboardDao;
use App\Model\Dashboard;
use App\View\AdminDashboardView;

class AdminDashboardController
{
    public static function manageDashboards(?string $action = null): void
    {
        AuthController::requireLogin();
        if (!AuthController::isAdmin()) {
            header("Location: ?p=dashboards");
            exit;
        }

        $feedback = $_SESSION['feedback'] ?? null;
        unset($_SESSION['feedback']);
        $dashboardParaEditar = null;
        $rolesDoDashboard = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? 'listar';
            $roles = $_POST['roles'] ?? []; // Pega os roles do formulário (checkboxes)

            try {
                switch ($action) {
                    case 'criar':
                        $dashboard = Dashboard::criarDashboard(null, $_POST['titulo'], $_POST['url_iframe']);
                        DashboardDao::cadastrar($dashboard, $roles);
                        $_SESSION['feedback'] = ['tipo' => 'sucesso', 'mensagem' => 'Dashboard criado com sucesso!'];
                        break;
                    
                    case 'editar':
                        $dashboard = Dashboard::criarDashboard((int)$_POST['id'], $_POST['titulo'], $_POST['url_iframe']);
                        DashboardDao::editar($dashboard, $roles);
                        $_SESSION['feedback'] = ['tipo' => 'sucesso', 'mensagem' => 'Dashboard alterado com sucesso!'];
                        break;
                }
            } catch (\Exception $e) {
                $_SESSION['feedback'] = ['tipo' => 'erro', 'mensagem' => $e->getMessage()];
            }

            header("Location: ?p=admin_dashboards");
            exit;
        }

        if ($action === 'excluir' && isset($_GET['id'])) {
            try {
                DashboardDao::excluir((int)$_GET['id']);
                $_SESSION['feedback'] = ['tipo' => 'sucesso', 'mensagem' => 'Dashboard excluído com sucesso!'];
            } catch (\Exception $e) {
                $_SESSION['feedback'] = ['tipo' => 'erro', 'mensagem' => 'Erro ao excluir dashboard.'];
            }
            header("Location: ?p=admin_dashboards");
            exit;
        }
        
        if ($action === 'editar' && isset($_GET['id'])) {
            $dashboardParaEditar = DashboardDao::buscarPorId((int)$_GET['id']);
            if ($dashboardParaEditar) {
                $rolesDoDashboard = DashboardDao::getRoles($dashboardParaEditar->getId());
            }
        }

        $dashboards = DashboardDao::listarTodos();
        AdminDashboardView::displayManagementPage($dashboards, $feedback, $dashboardParaEditar, $rolesDoDashboard);
    }
}