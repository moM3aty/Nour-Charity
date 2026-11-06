<?php
// app/Controllers/Admin/AdminCategoryController.php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Session;
use App\Models\Category;

final class AdminCategoryController extends Controller
{
    public function index(): void
    {
        $this->requireAuth('content.manage');
        $this->view('admin/categories/index', [
            'title' => 'تصنيفات التبرع',
            'items' => (new Category())->all('sort_order, name'),
        ], 'admin');
    }

    public function save(): void
    {
        $this->requireAuth('content.manage');
        $this->requirePost();
        $name = trim((string)$this->input('name'));
        if ($name === '') { Session::flash('error', 'الاسم مطلوب.'); $this->redirect('admin/categories'); }
        $slug = slugify(trim((string)$this->input('slug', $name)));
        $base = $slug; $i = 1;
        $existingId = (int)$this->input('id', 0);
        while (true) {
            $dup = Database::fetch("SELECT id FROM categories WHERE slug = ? AND id <> ?", [$slug, $existingId]);
            if (!$dup) break;
            $slug = $base . '-' . (++$i);
        }

        $data = [
            'name'        => $name,
            'slug'        => $slug,
            'description' => trim((string)$this->input('description', '')) ?: null,
            'icon'        => trim((string)$this->input('icon', '')) ?: null,
            'sort_order'  => (int)$this->input('sort_order', 0),
            'is_active'   => (int)($this->input('is_active', 0)) ? 1 : 0,
        ];
        if (!empty($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $img = store_public_upload($_FILES['image'], 'categories', config('uploads.allowed_images'));
            if ($img) $data['image'] = $img;
        }
        if ($existingId > 0) {
            (new Category())->update($existingId, $data);
            Session::flash('success', 'تم تحديث التصنيف.');
        } else {
            (new Category())->create($data);
            Session::flash('success', 'تمت الإضافة.');
        }
        $this->redirect('admin/categories');
    }

    public function delete(string $id): void
    {
        $this->requireAuth('content.manage');
        $this->requirePost();
        $inUse = Database::fetch("SELECT COUNT(*) AS c FROM projects WHERE category_id = ? AND deleted_at IS NULL", [(int)$id]);
        if (((int)($inUse['c'] ?? 0)) > 0) {
            Session::flash('error', 'لا يمكن حذف تصنيف مستخدم في مشاريع.');
            $this->redirect('admin/categories');
        }
        (new Category())->delete((int)$id);
        Session::flash('success', 'تم الحذف.');
        $this->redirect('admin/categories');
    }
}