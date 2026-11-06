<?php
// app/Controllers/Admin/AdminAuditController.php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Database;

final class AdminAuditController extends Controller
{
    public function index(): void
    {
        $this->requireAuth('audit.view');
        $filters = [
            'q'      => trim((string)($_GET['q'] ?? '')),
            'action' => trim((string)($_GET['action'] ?? '')),
        ];
        $page = max(1, (int)($_GET['page'] ?? 1));
        $per = 40;
        $offset = ($page - 1) * $per;

        $where = ['1=1'];
        $params = [];
        if ($filters['q'] !== '') {
            $where[] = "(description LIKE ? OR entity_type LIKE ?)";
            $params[] = '%' . $filters['q'] . '%';
            $params[] = '%' . $filters['q'] . '%';
        }
        if ($filters['action'] !== '') {
            $where[] = "action = ?";
            $params[] = $filters['action'];
        }
        $w = implode(' AND ', $where);

        $items = Database::fetchAll(
            "SELECT a.*, u.name AS user_name
             FROM audit_logs a LEFT JOIN users u ON u.id = a.user_id
             WHERE $w ORDER BY a.created_at DESC LIMIT $per OFFSET $offset",
            $params
        );
        $total = (int)(Database::fetch("SELECT COUNT(*) AS c FROM audit_logs WHERE $w", $params)['c'] ?? 0);
        $actions = Database::fetchAll("SELECT DISTINCT action FROM audit_logs ORDER BY action");

        $this->view('admin/audit/index', [
            'title'   => 'سجل التدقيق',
            'items'   => $items,
            'total'   => $total,
            'page'    => $page,
            'pages'   => (int)ceil($total / $per),
            'filters' => $filters,
            'actions' => $actions,
        ], 'admin');
    }
}