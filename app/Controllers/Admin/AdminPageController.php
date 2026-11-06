<?php
// app/Controllers/Admin/AdminPageController.php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Session;
use App\Models\Page;
use App\Models\PageSection;

final class AdminPageController extends Controller
{
    public function index(): void
    {
        $this->requireAuth('content.manage');
        $this->view('admin/pages/index', [
            'title' => 'الصفحات',
            'items' => (new Page())->all('slug ASC'),
        ], 'admin');
    }

    public function edit(string $id): void
    {
        $this->requireAuth('content.manage');
        $page = (new Page())->find((int)$id);
        if (!$page) { http_response_code(404); exit; }
        $this->view('admin/pages/edit', [
            'title' => 'تعديل: ' . $page['title'],
            'page'  => $page,
        ], 'admin');
    }

    public function save(string $id): void
    {
        $this->requireAuth('content.manage');
        $this->requirePost();
        $data = [
            'title'            => trim((string)$this->input('title', '')),
            'content'          => sanitize_html((string)$this->input('content', '')),
            'meta_title'       => trim((string)$this->input('meta_title', '')) ?: null,
            'meta_description' => trim((string)$this->input('meta_description', '')) ?: null,
            'status'           => in_array($this->input('status'), ['draft','published'], true) ? $this->input('status') : 'draft',
        ];
        (new Page())->update((int)$id, $data);
        \App\Core\AuditLog::record('page.update', 'page', (int)$id);
        Session::flash('success', 'تم حفظ الصفحة.');
        $this->redirect('admin/pages/' . $id);
    }

    /**
     * About page sections editor
     */
    public function aboutSections(): void
    {
        $this->requireAuth('content.manage');
        $sections = (new PageSection())->forPage('about');
        $this->view('admin/pages/about', [
            'title'    => 'أقسام صفحة من نحن',
            'sections' => $sections,
        ], 'admin');
    }

    public function aboutSectionsSave(): void
    {
        $this->requireAuth('content.manage');
        $this->requirePost();
        $keys = ['story','vision','mission','values','objectives'];
        $model = new PageSection();
        foreach ($keys as $key) {
            $title = trim((string)$this->input($key . '_title', ''));
            $content = sanitize_html((string)$this->input($key . '_content', ''));
            if ($title === '' && $content === '') continue;
            $model->upsert('about', $key, [
                'title'   => $title,
                'content' => $content,
                'is_active' => 1,
            ]);
        }
        \App\Core\AuditLog::record('about.update', 'page_sections');
        Session::flash('success', 'تم حفظ أقسام صفحة من نحن.');
        $this->redirect('admin/pages/about');
    }
}