<?php
// app/Controllers/Admin/AdminDashboardController.php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Database;

final class AdminDashboardController extends Controller
{
    public function index(): void
    {
        $this->requireAuth();

        $stats = [
            'total_donations'       => (float)(Database::fetch("SELECT COALESCE(SUM(amount),0) AS s FROM donations WHERE status='paid'")['s'] ?? 0),
            'awaiting_verification' => (int)(Database::fetch("SELECT COUNT(*) AS c FROM donations WHERE status='awaiting_verification'")['c'] ?? 0),
            'active_projects'       => (int)(Database::fetch("SELECT COUNT(*) AS c FROM projects WHERE status='active' AND deleted_at IS NULL")['c'] ?? 0),
            'completed_projects'    => (int)(Database::fetch("SELECT COUNT(*) AS c FROM projects WHERE status='completed' AND deleted_at IS NULL")['c'] ?? 0),
            'volunteer_apps'        => (int)(Database::fetch("SELECT COUNT(*) AS c FROM volunteer_applications WHERE status='new'")['c'] ?? 0),
            'beneficiary_requests'  => (int)(Database::fetch("SELECT COUNT(*) AS c FROM assistance_requests WHERE status='new'")['c'] ?? 0),
            'contact_messages'      => (int)(Database::fetch("SELECT COUNT(*) AS c FROM contact_messages WHERE status='new'")['c'] ?? 0),
            'month_total'           => (float)(Database::fetch("SELECT COALESCE(SUM(amount),0) AS s FROM donations WHERE status='paid' AND created_at >= DATE_FORMAT(NOW(), '%Y-%m-01')")['s'] ?? 0),
        ];

        $trend = Database::fetchAll(
            "SELECT DATE(created_at) AS d, SUM(amount) AS total
             FROM donations
             WHERE status = 'paid' AND created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
             GROUP BY DATE(created_at) ORDER BY d ASC"
        );
        $trendMap = [];
        foreach ($trend as $t) $trendMap[$t['d']] = (float)$t['total'];
        $trendLabels = [];
        $trendData = [];
        for ($i = 29; $i >= 0; $i--) {
            $d = date('Y-m-d', strtotime("-$i days"));
            $trendLabels[] = date('m/d', strtotime($d));
            $trendData[] = $trendMap[$d] ?? 0;
        }

        $topProjects = Database::fetchAll(
            "SELECT p.id, p.title, p.target_amount, p.raised_amount
             FROM projects p
             WHERE p.status='active' AND p.deleted_at IS NULL
             ORDER BY p.raised_amount DESC LIMIT 5"
        );

        $recentDonations = Database::fetchAll(
            "SELECT d.*, COALESCE(p.title,'تبرع عام') AS project_title, dn.name AS donor_name
             FROM donations d
             LEFT JOIN projects p ON p.id = d.project_id
             LEFT JOIN donors dn ON dn.id = d.donor_id
             ORDER BY d.created_at DESC LIMIT 8"
        );

        $recentMessages = Database::fetchAll(
            "SELECT * FROM contact_messages ORDER BY created_at DESC LIMIT 5"
        );

        $this->view('admin/dashboard', [
            'title'           => 'لوحة التحكم',
            'stats'           => $stats,
            'trendLabels'     => $trendLabels,
            'trendData'       => $trendData,
            'topProjects'     => $topProjects,
            'recentDonations' => $recentDonations,
            'recentMessages'  => $recentMessages,
        ], 'admin');
    }

    /** ✅ نقطة جديدة: تحديث لحظي للإحصائيات (AJAX كل 30 ثانية) */
    public function stats(): void
    {
        $this->requireAuth();
        $stats = [
            'total_donations'       => (float)(Database::fetch("SELECT COALESCE(SUM(amount),0) AS s FROM donations WHERE status='paid'")['s'] ?? 0),
            'awaiting_verification' => (int)(Database::fetch("SELECT COUNT(*) AS c FROM donations WHERE status='awaiting_verification'")['c'] ?? 0),
            'active_projects'       => (int)(Database::fetch("SELECT COUNT(*) AS c FROM projects WHERE status='active' AND deleted_at IS NULL")['c'] ?? 0),
            'completed_projects'    => (int)(Database::fetch("SELECT COUNT(*) AS c FROM projects WHERE status='completed' AND deleted_at IS NULL")['c'] ?? 0),
            'volunteer_apps'        => (int)(Database::fetch("SELECT COUNT(*) AS c FROM volunteer_applications WHERE status='new'")['c'] ?? 0),
            'beneficiary_requests'  => (int)(Database::fetch("SELECT COUNT(*) AS c FROM assistance_requests WHERE status='new'")['c'] ?? 0),
            'contact_messages'      => (int)(Database::fetch("SELECT COUNT(*) AS c FROM contact_messages WHERE status='new'")['c'] ?? 0),
            'month_total'           => (float)(Database::fetch("SELECT COALESCE(SUM(amount),0) AS s FROM donations WHERE status='paid' AND created_at >= DATE_FORMAT(NOW(), '%Y-%m-01')")['s'] ?? 0),
        ];
        $stats['new_requests_total'] = $stats['volunteer_apps'] + $stats['beneficiary_requests'] + $stats['contact_messages'];
        $this->json($stats);
    }
}