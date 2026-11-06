<?php
// app/Controllers/Admin/AdminProjectController.php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Session;
use App\Models\Category;
use App\Models\Project;
use App\Models\ProjectImage;
use App\Models\ProjectUpdate;

final class AdminProjectController extends Controller
{
    public function index(): void
    {
        $this->requireAuth('projects.manage');
        $status = (string)($_GET['status'] ?? '');
        $q = trim((string)($_GET['q'] ?? ''));

        $where = ["p.deleted_at IS NULL"];
        $params = [];
        if ($status !== '') { $where[] = "p.status = ?"; $params[] = $status; }
        if ($q !== '') {
            $where[] = "(p.title LIKE ? OR p.slug LIKE ?)";
            $params[] = "%$q%"; $params[] = "%$q%";
        }
        $w = implode(' AND ', $where);

        $items = Database::fetchAll(
            "SELECT p.*, c.name AS category_name FROM projects p
             LEFT JOIN categories c ON c.id = p.category_id
             WHERE $w ORDER BY p.created_at DESC",
            $params
        );

        $this->view('admin/projects/index', [
            'title'  => 'المشاريع',
            'items'  => $items,
            'status' => $status,
            'q'      => $q,
        ], 'admin');
    }

    public function create(): void
    {
        $this->requireAuth('projects.manage');
        $this->view('admin/projects/form', [
            'title'      => 'مشروع جديد',
            'project'    => null,
            'categories' => (new Category())->active(),
        ], 'admin');
    }

    public function store(): void
    {
        $this->requireAuth('projects.manage');
        $this->requirePost();

        $title = trim((string)$this->input('title'));
        if ($title === '') {
            Session::flash('error', 'العنوان مطلوب.');
            $this->redirect('admin/projects/create');
        }
        $slug = slugify(trim((string)$this->input('slug', $title)));
        $i = 1; $base = $slug;
        while (Database::fetch("SELECT id FROM projects WHERE slug = ?", [$slug])) {
            $slug = $base . '-' . (++$i);
        }

        $cover = null;
        if (!empty($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
            $cover = store_public_upload($_FILES['cover_image'], 'projects', config('uploads.allowed_images'));
        }

        $data = [
            'category_id'       => (int)$this->input('category_id', 0) ?: null,
            'title'             => $title,
            'slug'              => $slug,
            'short_description' => trim((string)$this->input('short_description', '')) ?: null,
            'description'       => sanitize_html((string)$this->input('description', '')),
            'objectives'        => sanitize_html((string)$this->input('objectives', '')),
            'beneficiaries'     => trim((string)$this->input('beneficiaries', '')) ?: null,
            'cover_image'       => $cover,
            'target_amount'     => (float)$this->input('target_amount', 0),
            'currency'          => setting('site.currency', 'SAR'),
            'status'            => in_array($this->input('status'), ['draft','active','funded','completed','paused','archived'], true) ? $this->input('status') : 'draft',
            'is_featured'       => (int)($this->input('is_featured', 0)) ? 1 : 0,
            'start_date'        => $this->input('start_date', '') ?: null,
            'end_date'          => $this->input('end_date', '') ?: null,
            'meta_title'        => trim((string)$this->input('meta_title', '')) ?: null,
            'meta_description'  => trim((string)$this->input('meta_description', '')) ?: null,
        ];

        $id = (new Project())->create($data);
        \App\Core\AuditLog::record('project.create', 'project', $id, "Created: $title");
        Session::flash('success', 'تم إنشاء المشروع.');
        // ✅ التوجيه الصحيح
        $this->redirect('admin/projects/' . $id . '/edit');
    }

    public function edit(string $id): void
    {
        $this->requireAuth('projects.manage');
        $p = (new Project())->find((int)$id);
        if (!$p) { http_response_code(404); exit; }
        $this->view('admin/projects/form', [
            'title'      => 'تعديل: ' . $p['title'],
            'project'    => $p,
            'categories' => (new Category())->active(),
            'images'     => (new ProjectImage())->forProject((int)$id),
            'updates'    => (new ProjectUpdate())->forProject((int)$id),
        ], 'admin');
    }

    public function update(string $id): void
    {
        $this->requireAuth('projects.manage');
        $this->requirePost();
        $p = (new Project())->find((int)$id);
        if (!$p) { http_response_code(404); exit; }

        $data = [
            'category_id'       => (int)$this->input('category_id', 0) ?: null,
            'title'             => trim((string)$this->input('title')),
            'short_description' => trim((string)$this->input('short_description', '')) ?: null,
            'description'       => sanitize_html((string)$this->input('description', '')),
            'objectives'        => sanitize_html((string)$this->input('objectives', '')),
            'beneficiaries'     => trim((string)$this->input('beneficiaries', '')) ?: null,
            'target_amount'     => (float)$this->input('target_amount', 0),
            'status'            => in_array($this->input('status'), ['draft','active','funded','completed','paused','archived'], true) ? $this->input('status') : 'draft',
            'is_featured'       => (int)($this->input('is_featured', 0)) ? 1 : 0,
            'start_date'        => $this->input('start_date', '') ?: null,
            'end_date'          => $this->input('end_date', '') ?: null,
            'meta_title'        => trim((string)$this->input('meta_title', '')) ?: null,
            'meta_description'  => trim((string)$this->input('meta_description', '')) ?: null,
        ];

        if (!empty($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
            $cover = store_public_upload($_FILES['cover_image'], 'projects', config('uploads.allowed_images'));
            if ($cover) $data['cover_image'] = $cover;
        }

        (new Project())->update((int)$id, $data);
        \App\Core\AuditLog::record('project.update', 'project', (int)$id);
        Session::flash('success', 'تم تحديث المشروع.');
        // ✅ التوجيه الصحيح
        $this->redirect('admin/projects/' . $id . '/edit');
    }

    public function delete(string $id): void
    {
        $this->requireAuth('projects.manage');
        $this->requirePost();
        Database::execute("UPDATE projects SET deleted_at = NOW(), status = 'archived' WHERE id = ?", [(int)$id]);
        \App\Core\AuditLog::record('project.archive', 'project', (int)$id);
        Session::flash('success', 'تم أرشفة المشروع.');
        $this->redirect('admin/projects');
    }

    /* ---------- Images ---------- */
    public function uploadImage(string $id): void
    {
        $this->requireAuth('projects.manage');
        $this->requirePost();
        if (!empty($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $path = store_public_upload($_FILES['image'], 'projects/gallery', config('uploads.allowed_images'));
            if ($path) {
                Database::execute(
                    "INSERT INTO project_images (project_id, image_path, caption, sort_order) VALUES (?,?,?,?)",
                    [(int)$id, $path, trim((string)$this->input('caption', '')) ?: null, (int)$this->input('sort_order', 0)]
                );
                Session::flash('success', 'تم رفع الصورة.');
            } else {
                Session::flash('error', 'فشل رفع الصورة.');
            }
        }
        $this->redirect('admin/projects/' . $id . '/edit');
    }

    public function deleteImage(string $projectId, string $imageId): void
    {
        $this->requireAuth('projects.manage');
        $this->requirePost();
        Database::execute("DELETE FROM project_images WHERE id = ? AND project_id = ?", [(int)$imageId, (int)$projectId]);
        Session::flash('success', 'تم حذف الصورة.');
        $this->redirect('admin/projects/' . $projectId . '/edit');
    }

    /* ---------- Updates ---------- */
    public function addUpdate(string $id): void
    {
        $this->requireAuth('projects.manage');
        $this->requirePost();
        $title = trim((string)$this->input('update_title', ''));
        $content = sanitize_html((string)$this->input('update_content', ''));
        if ($title === '' || $content === '') {
            Session::flash('error', 'العنوان والمحتوى مطلوبان.');
            $this->redirect('admin/projects/' . $id . '/edit');
        }
        Database::execute(
            "INSERT INTO project_updates (project_id, title, content, created_at) VALUES (?,?,?,NOW())",
            [(int)$id, $title, $content]
        );
        Session::flash('success', 'تمت إضافة التحديث.');
        $this->redirect('admin/projects/' . $id . '/edit');
    }

    public function deleteUpdate(string $projectId, string $updateId): void
    {
        $this->requireAuth('projects.manage');
        $this->requirePost();
        Database::execute("DELETE FROM project_updates WHERE id = ? AND project_id = ?", [(int)$updateId, (int)$projectId]);
        Session::flash('success', 'تم حذف التحديث.');
        $this->redirect('admin/projects/' . $projectId . '/edit');
    }
}