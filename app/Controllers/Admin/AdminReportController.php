<?php
// app/Controllers/Admin/AdminReportController.php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Database;

final class AdminReportController extends Controller
{
    public function index(): void
    {
        $this->requireAuth('reports.view');

        $from = trim((string)($_GET['from'] ?? date('Y-m-01')));
        $to   = trim((string)($_GET['to'] ?? date('Y-m-d')));

        // Summary
        $summary = Database::fetch(
            "SELECT
                COUNT(*) AS count,
                COALESCE(SUM(amount),0) AS total,
                COALESCE(AVG(amount),0) AS avg
             FROM donations
             WHERE status='paid' AND DATE(created_at) BETWEEN ? AND ?",
            [$from, $to]
        );

        // By project
        $byProject = Database::fetchAll(
            "SELECT p.id, p.title, COUNT(d.id) AS donations_count, COALESCE(SUM(d.amount),0) AS total
             FROM projects p
             LEFT JOIN donations d ON d.project_id = p.id AND d.status='paid'
                AND DATE(d.created_at) BETWEEN ? AND ?
             WHERE p.deleted_at IS NULL
             GROUP BY p.id, p.title
             HAVING donations_count > 0
             ORDER BY total DESC",
            [$from, $to]
        );

        // By status (all donations in range)
        $byStatus = Database::fetchAll(
            "SELECT status, COUNT(*) AS count, COALESCE(SUM(amount),0) AS total
             FROM donations
             WHERE DATE(created_at) BETWEEN ? AND ?
             GROUP BY status",
            [$from, $to]
        );

        // Daily breakdown
        $daily = Database::fetchAll(
            "SELECT DATE(created_at) AS d, COUNT(*) AS count, SUM(amount) AS total
             FROM donations
             WHERE status='paid' AND DATE(created_at) BETWEEN ? AND ?
             GROUP BY DATE(created_at) ORDER BY d ASC",
            [$from, $to]
        );

        $this->view('admin/reports/index', [
            'title'    => 'التقارير',
            'from'     => $from,
            'to'       => $to,
            'summary'  => $summary,
            'byProject'=> $byProject,
            'byStatus' => $byStatus,
            'daily'    => $daily,
        ], 'admin');
    }

    public function exportCsv(): void
    {
        $this->requireAuth('reports.view');
        $from = trim((string)($_GET['from'] ?? date('Y-m-01')));
        $to   = trim((string)($_GET['to'] ?? date('Y-m-d')));

        $rows = Database::fetchAll(
            "SELECT d.donation_number, d.amount, d.currency, d.status, d.payment_method,
                    d.created_at, p.title AS project, dn.name AS donor_name, dn.email AS donor_email
             FROM donations d
             LEFT JOIN projects p ON p.id = d.project_id
             LEFT JOIN donors dn ON dn.id = d.donor_id
             WHERE DATE(d.created_at) BETWEEN ? AND ?
             ORDER BY d.created_at DESC",
            [$from, $to]
        );

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="report-' . $from . '-to-' . $to . '.csv"');
        $out = fopen('php://output', 'w');
        fprintf($out, chr(0xEF).chr(0xBB).chr(0xBF));
        fputcsv($out, ['الرقم','المبلغ','العملة','الحالة','طريقة الدفع','التاريخ','المشروع','المتبرع','البريد']);
        foreach ($rows as $r) {
            fputcsv($out, [$r['donation_number'], $r['amount'], $r['currency'], $r['status'], $r['payment_method'], $r['created_at'], $r['project'], $r['donor_name'], $r['donor_email']]);
        }
        fclose($out);
        exit;
    }

    public function print(): void
    {
        $this->requireAuth('reports.view');
        $from = trim((string)($_GET['from'] ?? date('Y-m-01')));
        $to   = trim((string)($_GET['to'] ?? date('Y-m-d')));

        $summary = Database::fetch(
            "SELECT COUNT(*) AS count, COALESCE(SUM(amount),0) AS total
             FROM donations WHERE status='paid' AND DATE(created_at) BETWEEN ? AND ?",
            [$from, $to]
        );
        $byProject = Database::fetchAll(
            "SELECT p.title, COUNT(d.id) AS donations_count, COALESCE(SUM(d.amount),0) AS total
             FROM projects p LEFT JOIN donations d ON d.project_id = p.id AND d.status='paid' AND DATE(d.created_at) BETWEEN ? AND ?
             WHERE p.deleted_at IS NULL GROUP BY p.id HAVING donations_count > 0 ORDER BY total DESC",
            [$from, $to]
        );
        $this->view('admin/reports/print', [
            'from' => $from, 'to' => $to, 'summary' => $summary, 'byProject' => $byProject,
        ], 'bare');
    }
}