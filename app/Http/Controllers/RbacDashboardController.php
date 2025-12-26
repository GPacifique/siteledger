<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Services\DashboardStatsService;
use App\Services\RbacFilterService;

class RbacDashboardController extends Controller
{
    protected $statsService;
    protected $rbacFilterService;

    public function __construct(DashboardStatsService $statsService, RbacFilterService $rbacFilterService)
    {
        $this->statsService = $statsService;
        $this->rbacFilterService = $rbacFilterService;
    }

    public function admin()
    {
        return Inertia::render('Dashboards/Admin', [
            'stats' => $this->statsService->getAdminStats(),
            'dailyStats' => $this->statsService->getDailyStats(30),
            'weeklyStats' => $this->statsService->getWeeklyStats(12),
            'financialSummary' => $this->statsService->getFinancialSummary(),
            'topProjects' => $this->statsService->getTopProjects(5),
            'recentPayments' => $this->statsService->getQuickStats(),
            'cashFlowAnalysis' => $this->statsService->getCashFlowAnalysis(6),
            'paymentStatusBreakdown' => $this->statsService->getPaymentStatusBreakdown(),
            'filterContext' => $this->rbacFilterService->getFilterContext(),
        ]);
    }

    public function accountant()
    {
        return Inertia::render('Dashboards/Accountant', [
            'stats' => $this->statsService->getAccountantStats()
        ]);
    }

    public function siteManager()
    {
        return Inertia::render('Dashboards/SiteManager', [
            'stats' => $this->statsService->getSiteManagerStats()
        ]);
    }

    public function storeKeeper()
    {
        return Inertia::render('Dashboards/StoreKeeper', [
            'stats' => $this->statsService->getStoreKeeperStats()
        ]);
    }

    public function systemAdmin()
    {
        return Inertia::render('Dashboards/SystemAdmin', [
            'stats' => $this->statsService->getSystemAdminStats()
        ]);
    }
}
