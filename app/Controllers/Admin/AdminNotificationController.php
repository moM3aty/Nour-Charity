<?php
// app/Controllers/Admin/AdminNotificationController.php
namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\Notification;

final class AdminNotificationController extends Controller
{
    public function index(): void
    {
        $this->requireAuth();
        $this->view('admin/notifications/index', [
            'title' => 'الإشعارات',
            'items' => (new Notification())->forUser(Auth::id(), 100),
        ], 'admin');
    }

    public function markRead(string $id): void
    {
        $this->requireAuth();
        $this->requirePost();
        (new Notification())->markRead((int)$id);
        $this->json(['ok' => true]);
    }

    public function markAllRead(): void
    {
        $this->requireAuth();
        $this->requirePost();
        (new Notification())->markAllRead(Auth::id());
        $this->redirect('admin/notifications');
    }
}