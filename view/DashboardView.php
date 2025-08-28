<?php
namespace App\View;

use App\Model\Dashboard;

class DashboardView
{
    /**
     * @param Dashboard[] $dashboards
     */
    public static function displayDashboards(array $dashboards): void
    {
        require_once(__DIR__ . '/dashboard/dashboard.php');
    }
}