<?php
// app/Controllers/Admin/AdminSettingsController.php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Settings;
use App\Core\Session;

final class AdminSettingsController extends Controller
{
    private function schema(): array
    {
        return [
            'general' => [
                'site.name_ar'      => ['اسم الموقع (عربي)', 'text'],
                'site.name_en'      => ['اسم الموقع (إنجليزي)', 'text'],
                'site.assoc_name_ar'=> ['الاسم الرسمي للجمعية', 'text'],
                'site.description'  => ['وصف الموقع', 'textarea'],
                'site.currency'     => ['العملة (كود)', 'text'],
                'site.currency_symbol' => ['رمز العملة', 'text'],
                'maintenance.mode'  => ['وضع الصيانة', 'boolean'],
            ],
            'brand' => [
                'site.logo'    => ['الشعار الأساسي', 'image'],
                'site.favicon' => ['أيقونة المتصفح (Favicon)', 'image'],
            ],
            'contact' => [
                'site.phone'    => ['رقم الهاتف', 'text'],
                'site.whatsapp' => ['رقم الواتساب', 'text'],
                'site.email'    => ['البريد الإلكتروني', 'text'],
                'site.address'  => ['العنوان', 'textarea'],
            ],
            'social' => [
                'site.facebook'  => ['رابط فيسبوك', 'text'],
                'site.twitter'   => ['رابط منصة X (تويتر)', 'text'],
                'site.instagram' => ['رابط إنستغرام', 'text'],
                'site.youtube'   => ['رابط يوتيوب', 'text'],
            ],
            'seo' => [
                'seo.default_title'       => ['عنوان SEO الافتراضي', 'text'],
                'seo.default_description' => ['وصف SEO الافتراضي', 'textarea'],
            ],
            'donations' => [
                'donation.presets'      => ['المبالغ المقترحة (مفصولة بفاصلة)', 'text'],
                'donation.instructions' => ['تعليمات التبرع (تظهر في صفحة الدفع)', 'textarea'],
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
        if (!isset($schema[$group])) { 
            $this->redirect('admin/settings'); 
        }

        foreach ($schema[$group] as $key => $meta) {
            $postKey = str_replace('.', '_', $key);
            $type = $meta[1];
            
            if ($type === 'boolean') {
                $val = (int)($this->input($postKey, 0)) ? '1' : '0';
                Settings::set($key, $val);
                continue;
            }
            
            if ($type === 'image') {
                if (!empty($_FILES[$postKey]) && $_FILES[$postKey]['error'] === UPLOAD_ERR_OK) {
                    $uploaded = store_public_upload($_FILES[$postKey], 'branding', config('uploads.allowed_images'));
                    if ($uploaded) Settings::set($key, $uploaded);
                } else {
                    $keep = trim((string)$this->input($postKey . '_existing', ''));
                    if ($keep !== '') Settings::set($key, $keep);
                }
                continue;
            }
            
            $val = (string)$this->input($postKey, '');
            Settings::set($key, $val);
        }
        
        \App\Core\AuditLog::record('settings.update', 'settings', 0, "Group updated: $group");
        Session::flash('success', 'تم حفظ وتحديث الإعدادات بنجاح.');
        $this->redirect('admin/settings?group=' . urlencode($group));
    }
}