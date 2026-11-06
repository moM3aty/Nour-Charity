<?php
// app/Controllers/Admin/AdminArticleController.php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Session;
use App\Models\Article;

final class AdminArticleController extends Controller
{
    public function index(): void
    {
        $this->requireAuth('news.manage');
        $items = Database::fetchAll(
            "SELECT a.*, c.name AS category_name FROM articles a
             LEFT JOIN article_categories c ON c.id = a.category_id
             WHERE a.deleted_at IS NULL ORDER BY a.created_at DESC"
        );
        $this->view('admin/articles/index', ['title' => 'الأخبار', 'items' => $items], 'admin');
    }

    public function create(): void
    {
        $this->requireAuth('news.manage');
        $this->view('admin/articles/form', [
            'title'      => 'مقال جديد',
            'article'    => null,
            'categories' => Database::fetchAll("SELECT * FROM article_categories ORDER BY name"),
        ], 'admin');
    }

    public function store(): void
    {
        $this->requireAuth('news.manage');
        $this->requirePost();
        $title = trim((string)$this->input('title'));
        if ($title === '') { Session::flash('error', 'العنوان مطلوب.'); $this->redirect('admin/articles/create'); }
        $slug = slugify($title); $i = 1; $base = $slug;
        while (Database::fetch("SELECT id FROM articles WHERE slug = ?", [$slug])) $slug = $base . '-' . (++$i);

        $cover = null;
        if (!empty($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
            $cover = store_public_upload($_FILES['cover_image'], 'articles', config('uploads.allowed_images'));
        }
        $status = in_array($this->input('status'), ['draft','published'], true) ? $this->input('status') : 'draft';

        $id = (new Article())->create([
            'category_id'      => (int)$this->input('category_id', 0) ?: null,
            'title'            => $title,
            'slug'             => $slug,
            'excerpt'          => trim((string)$this->input('excerpt', '')) ?: null,
            'content'          => sanitize_html((string)$this->input('content', '')),
            'cover_image'      => $cover,
            'status'           => $status,
            'is_featured'      => (int)($this->input('is_featured', 0)) ? 1 : 0,
            'published_at'     => $status === 'published' ? date('Y-m-d H:i:s') : null,
            'meta_title'       => trim((string)$this->input('meta_title', '')) ?: null,
            'meta_description' => trim((string)$this->input('meta_description', '')) ?: null,
        ]);
        \App\Core\AuditLog::record('article.create', 'article', $id, $title);
        Session::flash('success', 'تم إنشاء المقال.');
        $this->redirect('admin/articles/' . $id . '/edit');
    }

    public function edit(string $id): void
    {
        $this->requireAuth('news.manage');
        $a = (new Article())->find((int)$id);
        if (!$a) { http_response_code(404); exit; }
        $this->view('admin/articles/form', [
            'title'      => 'تعديل: ' . $a['title'],
            'article'    => $a,
            'categories' => Database::fetchAll("SELECT * FROM article_categories ORDER BY name"),
        ], 'admin');
    }

    public function update(string $id): void
    {
        $this->requireAuth('news.manage');
        $this->requirePost();
        $a = (new Article())->find((int)$id);
        if (!$a) { http_response_code(404); exit; }
        $status = in_array($this->input('status'), ['draft','published'], true) ? $this->input('status') : 'draft';

        $data = [
            'category_id'      => (int)$this->input('category_id', 0) ?: null,
            'title'            => trim((string)$this->input('title')),
            'excerpt'          => trim((string)$this->input('excerpt', '')) ?: null,
            'content'          => sanitize_html((string)$this->input('content', '')),
            'status'           => $status,
            'is_featured'      => (int)($this->input('is_featured', 0)) ? 1 : 0,
            'meta_title'       => trim((string)$this->input('meta_title', '')) ?: null,
            'meta_description' => trim((string)$this->input('meta_description', '')) ?: null,
        ];
        if ($status === 'published' && empty($a['published_at'])) {
            $data['published_at'] = date('Y-m-d H:i:s');
        }
        if (!empty($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
            $cover = store_public_upload($_FILES['cover_image'], 'articles', config('uploads.allowed_images'));
            if ($cover) $data['cover_image'] = $cover;
        }
        (new Article())->update((int)$id, $data);
        \App\Core\AuditLog::record('article.update', 'article', (int)$id);
        Session::flash('success', 'تم التحديث.');
        $this->redirect('admin/articles/' . $id . '/edit');
    }

    public function delete(string $id): void
    {
        $this->requireAuth('news.manage');
        $this->requirePost();
        Database::execute("UPDATE articles SET deleted_at = NOW() WHERE id = ?", [(int)$id]);
        Session::flash('success', 'تم الحذف.');
        $this->redirect('admin/articles');
    }

    /* Categories */
    public function categories(): void
    {
        $this->requireAuth('news.manage');
        $this->view('admin/articles/categories', [
            'title' => 'تصنيفات الأخبار',
            'items' => Database::fetchAll("SELECT * FROM article_categories ORDER BY name"),
        ], 'admin');
    }

    public function categorySave(): void
    {
        $this->requireAuth('news.manage');
        $this->requirePost();
        $name = trim((string)$this->input('name'));
        if ($name === '') { Session::flash('error', 'الاسم مطلوب.'); $this->redirect('admin/articles/categories'); }
        $id = (int)$this->input('id', 0);
        $slug = slugify($name);
        if ($id > 0) {
            Database::execute("UPDATE article_categories SET name = ?, slug = ? WHERE id = ?", [$name, $slug, $id]);
        } else {
            Database::execute("INSERT INTO article_categories (name, slug) VALUES (?,?)", [$name, $slug]);
        }
        Session::flash('success', 'تم الحفظ.');
        $this->redirect('admin/articles/categories');
    }

    public function categoryDelete(string $id): void
    {
        $this->requireAuth('news.manage');
        $this->requirePost();
        Database::execute("DELETE FROM article_categories WHERE id = ?", [(int)$id]);
        Session::flash('success', 'تم الحذف.');
        $this->redirect('admin/articles/categories');
    }
}