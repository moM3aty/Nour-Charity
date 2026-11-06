<?php
//  app/Controllers/Admin/AdminCampaignController.php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Session;
use App\Models\Campaign;
use App\Models\Project;

final class AdminCampaignController extends Controller
{
    public function index(): void
    {
        $this->requireAuth('campaigns.manage');
        $items = Database::fetchAll(
            "SELECT c.*, p.title AS project_title FROM campaigns c
             LEFT JOIN projects p ON p.id = c.project_id
             ORDER BY c.created_at DESC"
        );
        $this->view('admin/campaigns/index', [
            'title' => 'الحملات',
            'items' => $items,
        ], 'admin');
    }

    public function create(): void
    {
        $this->requireAuth('campaigns.manage');
        $this->view('admin/campaigns/form', [
            'title'    => 'حملة جديدة',
            'campaign' => null,
            'projects' => (new Project())->all('title ASC'),
        ], 'admin');
    }

    public function store(): void
    {
        $this->requireAuth('campaigns.manage');
        $this->requirePost();
        $title = trim((string)$this->input('title'));
        if ($title === '') { Session::flash('error', 'العنوان مطلوب.'); $this->redirect('admin/campaigns/create'); }
        $slug = slugify($title); $i = 1; $base = $slug;
        while (Database::fetch("SELECT id FROM campaigns WHERE slug = ?", [$slug])) $slug = $base . '-' . (++$i);

        $image = null;
        if (!empty($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $image = store_public_upload($_FILES['image'], 'campaigns', config('uploads.allowed_images'));
        }

        $id = (new Campaign())->create([
            'project_id'    => (int)$this->input('project_id', 0) ?: null,
            'title'         => $title,
            'slug'          => $slug,
            'description'   => sanitize_html((string)$this->input('description', '')),
            'goal_amount'   => (float)$this->input('goal_amount', 0),
            'currency'      => setting('site.currency', 'SAR'),
            'image'         => $image,
            'start_date'    => $this->input('start_date', '') ?: null,
            'end_date'      => $this->input('end_date', '') ?: null,
            'status'        => in_array($this->input('status'), ['draft','active','ended','paused'], true) ? $this->input('status') : 'draft',
            'is_active'     => 1,
        ]);
        \App\Core\AuditLog::record('campaign.create', 'campaign', $id, $title);
        Session::flash('success', 'تم إنشاء الحملة.');
        $this->redirect('admin/campaigns/' . $id . '/edit');
    }

    public function edit(string $id): void
    {
        $this->requireAuth('campaigns.manage');
        $c = (new Campaign())->find((int)$id);
        if (!$c) { http_response_code(404); exit; }
        $this->view('admin/campaigns/form', [
            'title'    => 'تعديل حملة: ' . $c['title'],
            'campaign' => $c,
            'projects' => (new Project())->all('title ASC'),
        ], 'admin');
    }

    public function update(string $id): void
    {
        $this->requireAuth('campaigns.manage');
        $this->requirePost();
        $c = (new Campaign())->find((int)$id);
        if (!$c) { http_response_code(404); exit; }

        $data = [
            'project_id'    => (int)$this->input('project_id', 0) ?: null,
            'title'         => trim((string)$this->input('title')),
            'description'   => sanitize_html((string)$this->input('description', '')),
            'goal_amount'   => (float)$this->input('goal_amount', 0),
            'start_date'    => $this->input('start_date', '') ?: null,
            'end_date'      => $this->input('end_date', '') ?: null,
            'status'        => in_array($this->input('status'), ['draft','active','ended','paused'], true) ? $this->input('status') : 'draft',
            'is_active'     => (int)($this->input('is_active', 0)) ? 1 : 0,
        ];
        if (!empty($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $img = store_public_upload($_FILES['image'], 'campaigns', config('uploads.allowed_images'));
            if ($img) $data['image'] = $img;
        }
        (new Campaign())->update((int)$id, $data);
        \App\Core\AuditLog::record('campaign.update', 'campaign', (int)$id);
        Session::flash('success', 'تم تحديث الحملة.');
        $this->redirect('admin/campaigns/' . $id . '/edit');
    }

    public function delete(string $id): void
    {
        $this->requireAuth('campaigns.manage');
        $this->requirePost();
        (new Campaign())->delete((int)$id);
        \App\Core\AuditLog::record('campaign.delete', 'campaign', (int)$id);
        Session::flash('success', 'تم حذف الحملة.');
        $this->redirect('admin/campaigns');
    }
}