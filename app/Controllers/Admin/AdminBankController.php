<?php
// app/Controllers/Admin/AdminBankController.php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Session;
use App\Models\BankAccount;

final class AdminBankController extends Controller
{
    public function index(): void
    {
        $this->requireAuth('settings.manage');
        $this->view('admin/banks/index', [
            'title' => 'الحسابات البنكية',
            'banks' => (new BankAccount())->allOrdered(),
        ], 'admin');
    }

    public function store(): void
    {
        $this->requireAuth('settings.manage');
        $this->requirePost();
        $data = [
            'bank_name'      => trim((string)$this->input('bank_name', '')),
            'account_holder' => trim((string)$this->input('account_holder', '')),
            'account_number' => trim((string)$this->input('account_number', '')),
            'iban'           => trim((string)$this->input('iban', '')),
            'instructions'   => trim((string)$this->input('instructions', '')),
            'is_active'      => (int) ($this->input('is_active', 0)) ? 1 : 0,
            'sort_order'     => (int) $this->input('sort_order', 0),
        ];
        if ($data['bank_name'] === '' || $data['account_holder'] === '') {
            Session::flash('error', 'اسم البنك واسم صاحب الحساب مطلوبان.');
            $this->redirect('admin/banks');
        }
        $id = (int) $this->input('id', 0);
        if ($id > 0) {
            (new BankAccount())->update($id, $data);
            Session::flash('success', 'تم تحديث الحساب.');
        } else {
            (new BankAccount())->create($data);
            Session::flash('success', 'تم إضافة الحساب.');
        }
        $this->redirect('admin/banks');
    }

    public function delete(string $id): void
    {
        $this->requireAuth('settings.manage');
        $this->requirePost();
        (new BankAccount())->delete((int)$id);
        Session::flash('success', 'تم حذف الحساب.');
        $this->redirect('admin/banks');
    }
}