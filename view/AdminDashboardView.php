<?php
// view/AdminDashboardView.php

namespace App\View;

use App\Model\Dashboard;

class AdminDashboardView
{
    /**
     * @param Dashboard[] $dashboards
     * @param ?array $feedback
     * @param ?Dashboard $dashboardParaEditar
     * @param array $rolesDoDashboard
     */
    public static function displayManagementPage(array $dashboards, ?array $feedback, ?Dashboard $dashboardParaEditar = null, array $rolesDoDashboard = []): void
    {
        // A sidebar precisa da variável $dashboards para montar o menu
        require_once(__DIR__ . '/templates/sidebar.php');
        require_once(__DIR__ . '/admin/dashboards.php');
    }
}