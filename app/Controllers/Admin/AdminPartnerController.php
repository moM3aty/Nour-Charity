<?php
// app/Controllers/Admin/AdminPartnerController.php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Session;
use App\Models\Partner;

final class AdminPartnerController extends Controller
{
    public function index(): void
    {
        $this->requireAuth('partners.manage');
        $this->view('admin/partners/index', [
            'title' => 'الشركاء',
            'items' => (new Partner())->all('sort_order, name'),
        ], 'admin');
    }

    public function save(): void
    {
        $this->requireAuth('partners.manage');
        $this->requirePost();
        $data = [
            'name'       => trim((string)$this->input('name', '')),
            'url'        => trim((string)$this->input('url', '')) ?: null,
            'logo'       => trim((string)$this->input('logo_existing', '')) ?: null,
            'sort_order' => (int)$this->input('sort_order', 0),
            'is_active'  => (int)($this->input('is_active', 0)) ? 1 : 0,
        ];
        if ($data['name'] === '') {
            Session::flash('error', 'اسم الشريك مطلوب.');
            $this->redirect('admin/partners');
        }
        if (!empty($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
            $uploaded = store_public_upload($_FILES['logo'], 'partners', config('uploads.allowed_images'));
            if ($uploaded) $data['logo'] = $uploaded;
        }
        $id = (int)$this->input('id', 0);
        if ($id > 0) {
            (new Partner())->update($id, $data);
            Session::flash('success', 'تم التحديث.');
        } else {
            (new Partner())->create($data);
            Session::flash('success', 'تمت الإضافة.');
        }
        $this->redirect('admin/partners');
    }

    public function delete(string $id): void
    {
        $this->requireAuth('partners.manage');
        $this->requirePost();
        (new Partner())->delete((int)$id);
        Session::flash('success', 'تم الحذف.');
        $this->redirect('admin/partners');
    }
}