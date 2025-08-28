<?php
// view/DashboardView.php

namespace App\View;

use App\Model\Dashboard;

class DashboardView
{
    /**
     * @param Dashboard[] $dashboards
     */
    public static function displayDashboards(array $dashboards): void
    {
        // Inclui a sidebar, que agora é um componente reutilizável
        require_once(__DIR__ . '/./templates/sidebar.php');

        // Inclui o conteúdo específico da página de dashboards
        require_once(__DIR__ . '/dashboard/index.php');
    }
}