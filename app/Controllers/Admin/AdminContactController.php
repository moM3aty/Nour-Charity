<?php
// app/Controllers/Admin/AdminContactController.php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Session;
use App\Models\ContactMessage;

final class AdminContactController extends Controller
{
    public function index(): void
    {
        $this->requireAuth('content.manage');
        $filters = [
            'status' => trim((string)($_GET['status'] ?? '')),
            'q'      => trim((string)($_GET['q'] ?? '')),
        ];
        $page = max(1, (int)($_GET['page'] ?? 1));
        $data = (new ContactMessage())->paginate($filters, $page, 20);
        $this->view('admin/contact/index', [
            'title'   => 'الرسائل',
            'data'    => $data,
            'filters' => $filters,
        ], 'admin');
    }

    public function show(string $id): void
    {
        $this->requireAuth('content.manage');
        $msg = (new ContactMessage())->find((int)$id);
        if (!$msg) { http_response_code(404); exit; }
        (new ContactMessage())->markRead((int)$id);
        $this->view('admin/contact/show', [
            'title' => 'رسالة من ' . $msg['name'],
            'msg'   => $msg,
        ], 'admin');
    }

    public function updateStatus(string $id): void
    {
        $this->requireAuth('content.manage');
        $this->requirePost();
        $status = (string)$this->input('status');
        if (in_array($status, ['new','read','replied','archived'], true)) {
            (new ContactMessage())->update((int)$id, ['status' => $status]);
            Session::flash('success', 'تم تحديث الحالة.');
        }
        $this->redirect('admin/contact/' . $id);
    }
}