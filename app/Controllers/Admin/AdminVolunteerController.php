<?php
// app/Controllers/Admin/AdminVolunteerController.php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Session;
use App\Models\VolunteerApplication;
use App\Models\VolunteerOpportunity;

final class AdminVolunteerController extends Controller
{
    public function index(): void
    {
        $this->requireAuth('volunteers.manage');
        $filters = [
            'status' => trim((string)($_GET['status'] ?? '')),
            'q'      => trim((string)($_GET['q'] ?? '')),
        ];
        $page = max(1, (int)($_GET['page'] ?? 1));
        $data = (new VolunteerApplication())->paginate($filters, $page, 20);

        $this->view('admin/volunteers/index', [
            'title'   => 'طلبات التطوع',
            'data'    => $data,
            'filters' => $filters,
        ], 'admin');
    }

    public function show(string $id): void
    {
        $this->requireAuth('volunteers.manage');
        $app = (new VolunteerApplication())->find((int)$id);
        if (!$app) { http_response_code(404); exit('غير موجود'); }
        $this->view('admin/volunteers/show', [
            'title' => 'طلب تطوع: ' . $app['name'],
            'app'   => $app,
        ], 'admin');
    }

    public function updateStatus(string $id): void
    {
        $this->requireAuth('volunteers.manage');
        $this->requirePost();
        $status = (string)$this->input('status');
        $notes = trim((string)$this->input('notes', ''));
        (new VolunteerApplication())->updateStatus((int)$id, $status, $notes ?: null);
        Session::flash('success', 'تم تحديث حالة الطلب.');
        $this->redirect('admin/volunteers/' . $id);
    }

    public function downloadCv(string $id): void
    {
        $this->requireAuth('volunteers.manage');
        $app = (new VolunteerApplication())->find((int)$id);
        if (!$app || empty($app['cv_path'])) { http_response_code(404); exit('لا يوجد ملف'); }

        $private = config('uploads.private_path') ?? (BASE_PATH . '/storage/private');
        $path = $private . '/' . ltrim($app['cv_path'], '/');
        if (!is_file($path)) { http_response_code(404); exit('الملف غير موجود'); }

        header('Content-Type: application/octet-stream');
        header('Content-Length: ' . filesize($path));
        header('Content-Disposition: attachment; filename="CV-' . $app['id'] . '.' . pathinfo($path, PATHINFO_EXTENSION) . '"');
        readfile($path);
        exit;
    }

    public function exportCsv(): void
    {
        $this->requireAuth('volunteers.manage');
        $rows = Database::fetchAll("SELECT * FROM volunteer_applications ORDER BY created_at DESC");
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="volunteers-' . date('Ymd') . '.csv"');
        $out = fopen('php://output', 'w');
        fprintf($out, chr(0xEF).chr(0xBB).chr(0xBF));
        fputcsv($out, ['ID','الاسم','البريد','الهاتف','المدينة','الحالة','التاريخ']);
        foreach ($rows as $r) {
            fputcsv($out, [$r['id'], $r['name'], $r['email'], $r['phone'], $r['city'], $r['status'], $r['created_at']]);
        }
        fclose($out);
        exit;
    }

    /* Opportunities management */
    public function opportunities(): void
    {
        $this->requireAuth('volunteers.manage');
        $this->view('admin/volunteers/opportunities', [
            'title' => 'الفرص التطوعية',
            'items' => (new VolunteerOpportunity())->allOrdered(),
        ], 'admin');
    }

    public function opportunitySave(): void
    {
        $this->requireAuth('volunteers.manage');
        $this->requirePost();
        $data = [
            'title'       => trim((string)$this->input('title', '')),
            'slug'        => slugify(trim((string)$this->input('title', ''))),
            'description' => trim((string)$this->input('description', '')),
            'location'    => trim((string)$this->input('location', '')),
            'commitment'  => trim((string)$this->input('commitment', '')),
            'status'      => in_array($this->input('status'), ['open','closed'], true) ? $this->input('status') : 'open',
        ];
        if ($data['title'] === '') {
            Session::flash('error', 'العنوان مطلوب.');
            $this->redirect('admin/volunteers/opportunities');
        }
        $id = (int)$this->input('id', 0);
        if ($id > 0) {
            (new VolunteerOpportunity())->update($id, $data);
            Session::flash('success', 'تم التحديث.');
        } else {
            // ensure unique slug
            $base = $data['slug'];
            $i = 1;
            while (Database::fetch("SELECT id FROM volunteer_opportunities WHERE slug = ?", [$data['slug']])) {
                $data['slug'] = $base . '-' . (++$i);
            }
            (new VolunteerOpportunity())->create($data);
            Session::flash('success', 'تمت الإضافة.');
        }
        $this->redirect('admin/volunteers/opportunities');
    }

    public function opportunityDelete(string $id): void
    {
        $this->requireAuth('volunteers.manage');
        $this->requirePost();
        (new VolunteerOpportunity())->delete((int)$id);
        Session::flash('success', 'تم الحذف.');
        $this->redirect('admin/volunteers/opportunities');
    }
}