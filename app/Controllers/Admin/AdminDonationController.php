<?php
// app/Controllers/Admin/AdminDonationController.php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Session;
use App\Models\Donation;
use App\Models\Project;
use App\Models\TransferReceipt;

final class AdminDonationController extends Controller
{
    public function index(): void
    {
        $this->requireAuth('donations.manage');

        $filters = [
            'status'     => trim((string)($_GET['status'] ?? '')),
            'project_id' => (int)($_GET['project_id'] ?? 0) ?: null,
            'from'       => trim((string)($_GET['from'] ?? '')),
            'to'         => trim((string)($_GET['to'] ?? '')),
            'q'          => trim((string)($_GET['q'] ?? '')),
        ];
        $page = max(1, (int)($_GET['page'] ?? 1));
        $data = (new Donation())->paginate($filters, $page, 20);

        $this->view('admin/donations/index', [
            'title'    => 'التبرعات',
            'data'     => $data,
            'filters'  => $filters,
            'summary'  => (new Donation())->summary(),
            'projects' => (new Project())->all('title ASC'),
        ], 'admin');
    }

    public function show(string $id): void
    {
        $this->requireAuth('donations.manage');
        $d = (new Donation())->find((int)$id);
        if (!$d) { http_response_code(404); exit('غير موجود'); }

        $donor = $d['donor_id'] ? Database::fetch("SELECT * FROM donors WHERE id = ?", [$d['donor_id']]) : null;
        $project = $d['project_id'] ? Database::fetch("SELECT * FROM projects WHERE id = ?", [$d['project_id']]) : null;
        $receipt = (new TransferReceipt())->forDonation((int)$d['id']);

        $this->view('admin/donations/show', [
            'title'    => 'تبرع ' . $d['donation_number'],
            'donation' => $d,
            'donor'    => $donor,
            'project'  => $project,
            'receipt'  => $receipt,
            'items'    => (new Donation())->items((int)$d['id']),
        ], 'admin');
    }

    public function verify(string $id): void
    {
        $this->requireAuth('transfers.verify');
        $this->requirePost();
        $donation = (new Donation())->find((int)$id);
        if (!$donation) { http_response_code(404); exit; }

        $action = (string)$this->input('action');
        $reason = trim((string)$this->input('reason', ''));
        $userId = \App\Core\Auth::id();

        if ($action === 'approve') {
            (new Donation())->updateStatus((int)$donation['id'], 'paid', $userId, 'Manual verification by admin');
            // Mark receipt verified
            Database::execute(
                "UPDATE transfer_receipts SET verified_at = NOW(), verified_by = ? WHERE donation_id = ?",
                [$userId, $donation['id']]
            );
            Session::flash('success', 'تم اعتماد التبرع بنجاح.');
        } elseif ($action === 'reject') {
            (new Donation())->updateStatus((int)$donation['id'], 'failed', $userId, 'Rejected: ' . $reason);
            Session::flash('success', 'تم رفض التبرع.');
        }
        $this->redirect('admin/donations/' . $donation['id']);
    }

    public function viewReceipt(string $id): void
    {
        $this->requireAuth('transfers.verify');
        $receipt = (new TransferReceipt())->find((int)$id);
        if (!$receipt) { http_response_code(404); exit; }

        $private = config('uploads.private_path') ?? (BASE_PATH . '/storage/private');
        $path = $private . '/' . ltrim($receipt['file_path'], '/');
        if (!is_file($path)) { http_response_code(404); exit('الملف غير موجود'); }

        header('Content-Type: ' . ($receipt['mime_type'] ?: 'application/octet-stream'));
        header('Content-Length: ' . filesize($path));
        header('Content-Disposition: inline; filename="' . basename($path) . '"');
        readfile($path);
        exit;
    }

    public function exportCsv(): void
    {
        $this->requireAuth('donations.manage');
        $rows = Database::fetchAll(
            "SELECT d.donation_number, d.amount, d.currency, d.status, d.created_at,
                    p.title AS project, dn.name AS donor_name, dn.email AS donor_email
             FROM donations d
             LEFT JOIN projects p ON p.id = d.project_id
             LEFT JOIN donors dn ON dn.id = d.donor_id
             ORDER BY d.created_at DESC"
        );
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="donations-' . date('Ymd') . '.csv"');
        $out = fopen('php://output', 'w');
        fprintf($out, chr(0xEF).chr(0xBB).chr(0xBF));
        fputcsv($out, ['رقم التبرع','المبلغ','العملة','الحالة','التاريخ','المشروع','المتبرع','البريد']);
        foreach ($rows as $r) {
            fputcsv($out, [$r['donation_number'], $r['amount'], $r['currency'], $r['status'], $r['created_at'], $r['project'], $r['donor_name'], $r['donor_email']]);
        }
        fclose($out);
        exit;
    }
}