<?php
// app/Controllers/Admin/AdminHeroController.php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Session;
use App\Models\HeroSlide;

final class AdminHeroController extends Controller
{
    public function index(): void
    {
        $this->requireAuth('content.manage');
        $this->view('admin/hero/index', [
            'title' => 'سلايدر الصفحة الرئيسية',
            'items' => (new HeroSlide())->allOrdered(),
        ], 'admin');
    }

    public function save(): void
    {
        $this->requireAuth('content.manage');
        $this->requirePost();
        $title = trim((string)$this->input('title'));
        if ($title === '') { Session::flash('error', 'العنوان مطلوب.'); $this->redirect('admin/hero'); }

        $data = [
            'title'          => $title,
            'subtitle'       => trim((string)$this->input('subtitle', '')) ?: null,
            'description'    => trim((string)$this->input('description', '')) ?: null,
            'cta_text'       => trim((string)$this->input('cta_text', '')) ?: null,
            'cta_link'       => trim((string)$this->input('cta_link', '')) ?: null,
            'secondary_text' => trim((string)$this->input('secondary_text', '')) ?: null,
            'secondary_link' => trim((string)$this->input('secondary_link', '')) ?: null,
            'sort_order'     => (int)$this->input('sort_order', 0),
            'is_active'      => (int)($this->input('is_active', 0)) ? 1 : 0,
        ];
        if (!empty($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $img = store_public_upload($_FILES['image'], 'hero', config('uploads.allowed_images'));
            if ($img) $data['image'] = $img;
        }
        $id = (int)$this->input('id', 0);
        if ($id > 0) {
            (new HeroSlide())->update($id, $data);
        } else {
            (new HeroSlide())->create($data);
        }
        Session::flash('success', 'تم الحفظ.');
        $this->redirect('admin/hero');
    }

    public function delete(string $id): void
    {
        $this->requireAuth('content.manage');
        $this->requirePost();
        (new HeroSlide())->delete((int)$id);
        Session::flash('success', 'تم الحذف.');
        $this->redirect('admin/hero');
    }
}