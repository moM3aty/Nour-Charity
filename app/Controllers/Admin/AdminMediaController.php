<?php
// app/Controllers/Admin/AdminMediaController.php
namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Database;
use App\Core\Session;
use App\Models\Media;

final class AdminMediaController extends Controller
{
    public function index(): void
    {
        $this->requireAuth('media.manage');
        $filters = ['q' => trim((string)($_GET['q'] ?? '')), 'type' => trim((string)($_GET['type'] ?? ''))];
        $page = max(1, (int)($_GET['page'] ?? 1));
        $data = (new Media())->paginate($filters, $page, 24);
        $this->view('admin/media/index', [
            'title'   => 'مكتبة الوسائط',
            'data'    => $data,
            'filters' => $filters,
        ], 'admin');
    }

    /** ✅ رفع متعدد من صفحة المكتبة (HTML form) */
    public function upload(): void
    {
        $this->requireAuth('media.manage');
        $this->requirePost();
        $files = $_FILES['files'] ?? null;
        if (!$files) { Session::flash('error', 'لا يوجد ملف.'); $this->redirect('admin/media'); }

        $allowed = array_merge(config('uploads.allowed_images') ?? [], config('uploads.allowed_docs') ?? []);
        $count = is_array($files['name']) ? count($files['name']) : 1;
        $added = 0;
        for ($i = 0; $i < $count; $i++) {
            $file = is_array($files['name'])
                ? ['name'=>$files['name'][$i],'tmp_name'=>$files['tmp_name'][$i],'size'=>$files['size'][$i],'error'=>$files['error'][$i]]
                : $files;
            if ($file['error'] !== UPLOAD_ERR_OK) continue;
            $path = store_public_upload($file, 'media', $allowed);
            if ($path) {
                $this->insertMedia($path, $file);
                $added++;
            }
        }
        Session::flash('success', "تم رفع $added ملف.");
        $this->redirect('admin/media');
    }

    /**
     * ✅ نقطة جديدة: رفع صورة من محرر النصوص (TinyMCE)
     * ترجع JSON: { ok: true, url: "..." }
     */
    public function editorUpload(): void
    {
        // أي مدير مسجل ومعه أي صلاحية محتوى يمكنه رفع الصور
        $this->requireAuth();
        if (!Auth::canAny(['media.manage','content.manage','news.manage','projects.manage','campaigns.manage','settings.manage'])) {
            $this->json(['ok' => false, 'error' => 'لا تملك صلاحية رفع الصور.'], 403);
        }
        $this->requirePost();

        $file = $_FILES['file'] ?? null;
        if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
            $this->json(['ok' => false, 'error' => 'لم يتم رفع ملف أو حدث خطأ.'], 400);
        }

        $allowed = config('uploads.allowed_images') ?? [];
        $path = store_public_upload($file, 'media/editor', $allowed);
        if (!$path) {
            $this->json(['ok' => false, 'error' => 'صيغة أو حجم الملف غير مسموح.'], 400);
        }

        $this->insertMedia($path, $file);

        $this->json(['ok' => true, 'url' => $path]);
    }

    public function delete(string $id): void
    {
        $this->requireAuth('media.manage');
        $this->requirePost();
        $m = (new Media())->find((int)$id);
        if ($m && !empty($m['path'])) {
            $public = config('uploads.public_path') ?? (BASE_PATH . '/uploads');
            $file = $public . '/' . ltrim(str_replace(config('uploads.public_url') ?? '/uploads', '', $m['path']), '/');
            if (is_file($file)) @unlink($file);
            (new Media())->delete((int)$id);
        }
        Session::flash('success', 'تم الحذف.');
        $this->redirect('admin/media');
    }

    /** ✅ دالة داخلية: إدراج في media + قراءة MIME بشكل موثوق */
    private function insertMedia(string $path, array $file): void
    {
        $public = config('uploads.public_path') ?? (BASE_PATH . '/uploads');
        $publicUrl = rtrim(config('uploads.public_url') ?? '/uploads', '/');
        $rel = ltrim(str_replace($publicUrl, '', $path), '/');
        $full = $public . '/' . $rel;
        $mime = '';
        if (is_file($full)) {
            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            $mime = $finfo->file($full) ?: '';
        }
        Database::execute(
            "INSERT INTO media (filename, original_name, path, mime_type, size, uploaded_by, created_at)
             VALUES (?,?,?,?,?,?,NOW())",
            [
                basename($path),
                substr($file['name'] ?? 'file', 0, 200),
                $path,
                $mime,
                $file['size'] ?? 0,
                Auth::id(),
            ]
        );
    }
}