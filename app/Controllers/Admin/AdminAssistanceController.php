<?php
// app/Controllers/Admin/AdminAssistanceController.php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Session;
use App\Models\AssistanceRequest;

final class AdminAssistanceController extends Controller
{
    public function index(): void
    {
        $this->requireAuth('assistance.manage');
        $filters = [
            'status'   => trim((string)($_GET['status'] ?? '')),
            'category' => trim((string)($_GET['category'] ?? '')),
            'q'        => trim((string)($_GET['q'] ?? '')),
        ];
        $page = max(1, (int)($_GET['page'] ?? 1));
        $data = (new AssistanceRequest())->paginate($filters, $page, 20);

        $this->view('admin/assistance/index', [
            'title'   => 'طلبات المساعدة',
            'data'    => $data,
            'filters' => $filters,
        ], 'admin');
    }

    public function show(string $id): void
    {
        $this->requireAuth('assistance.manage');
        $req = (new AssistanceRequest())->find((int)$id);
        if (!$req) { http_response_code(404); exit('غير موجود'); }
        $this->view('admin/assistance/show', [
            'title' => 'طلب ' . $req['request_number'],
            'req'   => $req,
            'docs'  => (new AssistanceRequest())->documents((int)$req['id']),
        ], 'admin');
    }

    public function updateStatus(string $id): void
    {
        $this->requireAuth('assistance.manage');
        $this->requirePost();
        $status = (string)$this->input('status');
        $notes = trim((string)$this->input('notes', ''));
        (new AssistanceRequest())->updateStatus((int)$id, $status, $notes ?: null);
        Session::flash('success', 'تم التحديث.');
        $this->redirect('admin/assistance/' . $id);
    }

    public function downloadDoc(string $id): void
    {
        $this->requireAuth('assistance.manage');
        $doc = Database::fetch("SELECT * FROM assistance_documents WHERE id = ?", [(int)$id]);
        if (!$doc) { http_response_code(404); exit; }

        $private = config('uploads.private_path') ?? (BASE_PATH . '/storage/private');
        $path = $private . '/' . ltrim($doc['file_path'], '/');
        if (!is_file($path)) { http_response_code(404); exit; }

        header('Content-Type: application/octet-stream');
        header('Content-Length: ' . filesize($path));
        header('Content-Disposition: attachment; filename="' . ($doc['original_name'] ?: 'document') . '"');
        readfile($path);
        exit;
    }
}