<?php
// app/Controllers/Admin/AdminSettingsController.php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Settings;
use App\Core\Session;

final class AdminSettingsController extends Controller
{
    /** All editable settings organized by group. */
    private function schema(): array
    {
        return [
            'general' => [
                'site.name_ar'      => ['نص', 'text'],
                'site.name_en'      => ['نص', 'text'],
                'site.assoc_name_ar'=> ['اسم الجمعية', 'text'],
                'site.description'  => ['وصف الموقع', 'textarea'],
                'site.currency'     => ['العملة', 'text'],
                'site.currency_symbol' => ['رمز العملة', 'text'],
                'maintenance.mode'  => ['وضع الصيانة', 'boolean'],
            ],
            'brand' => [
                'site.logo'    => ['الشعار', 'image'],
                'site.favicon' => ['Favicon', 'image'],
                'brand.primary'   => ['اللون الأساسي', 'color'],
                'brand.navy'      => ['اللون الداكن', 'color'],
                'brand.secondary' => ['اللون الثانوي', 'color'],
                'brand.ice'       => ['اللون الفاتح', 'color'],
                'brand.gold'      => ['لون الذهبي', 'color'],
            ],
            'contact' => [
                'site.phone'    => ['الهاتف', 'text'],
                'site.whatsapp' => ['واتساب', 'text'],
                'site.email'    => ['البريد الإلكتروني', 'text'],
                'site.address'  => ['العنوان', 'textarea'],
            ],
            'social' => [
                'site.facebook'  => ['فيسبوك', 'text'],
                'site.twitter'   => ['تويتر/X', 'text'],
                'site.instagram' => ['إنستغرام', 'text'],
                'site.youtube'   => ['يوتيوب', 'text'],
            ],
            'seo' => [
                'seo.default_title'       => ['عنوان SEO', 'text'],
                'seo.default_description' => ['وصف SEO', 'textarea'],
            ],
            'hero' => [
                'hero.title'              => ['عنوان الهيرو', 'text'],
                'hero.description'        => ['وصف الهيرو', 'textarea'],
                'hero.cta_primary_text'   => ['نص الزر الرئيسي', 'text'],
                'hero.cta_primary_link'   => ['رابط الزر الرئيسي', 'text'],
                'hero.cta_secondary_text' => ['نص الزر الثانوي', 'text'],
                'hero.cta_secondary_link' => ['رابط الزر الثانوي', 'text'],
            ],
            'donations' => [
                'donation.presets'      => ['المبالغ المقترحة (مفصولة بفاصلة)', 'text'],
                'donation.instructions' => ['تعليمات التبرع', 'textarea'],
            ],
        ];
    }

    public function index(): void
    {
        $this->requireAuth('settings.manage');
        $schema = $this->schema();
        $values = [];
        foreach ($schema as $group => $fields) {
            foreach ($fields as $key => $_) {
                $values[$key] = Settings::get($key, '');
            }
        }
        $this->view('admin/settings/index', [
            'title'  => 'الإعدادات',
            'schema' => $schema,
            'values' => $values,
        ], 'admin');
    }

    public function save(): void
    {
        $this->requireAuth('settings.manage');
        $this->requirePost();
        $schema = $this->schema();
        $group = (string)$this->input('group', 'general');
        if (!isset($schema[$group])) { $this->redirect('admin/settings'); }

        foreach ($schema[$group] as $key => $meta) {
            $type = $meta[1];
            if ($type === 'boolean') {
                $val = (int)($this->input($key, 0)) ? '1' : '0';
                Settings::set($key, $val);
                continue;
            }
            if ($type === 'image') {
                // upload optional
                if (!empty($_FILES[$key]) && $_FILES[$key]['error'] === UPLOAD_ERR_OK) {
                    $uploaded = store_public_upload($_FILES[$key], 'branding', config('uploads.allowed_images'));
                    if ($uploaded) Settings::set($key, $uploaded);
                } else {
                    $keep = trim((string)$this->input($key . '_existing', ''));
                    if ($keep !== '') Settings::set($key, $keep);
                }
                continue;
            }
            $val = (string)$this->input($key, '');
            Settings::set($key, $val);
        }
        \App\Core\AuditLog::record('settings.update', 'settings', 0, "Group: $group");
        Session::flash('success', 'تم حفظ الإعدادات.');
        $this->redirect('admin/settings?group=' . urlencode($group));
    }
}