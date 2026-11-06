<?php
// app/Controllers/Frontend/PageController.php
namespace App\Controllers\Frontend;

use App\Core\Controller;
use App\Core\Database;
use App\Models\BankAccount;

final class PageController extends Controller
{
    public function about(): void
    {
        $this->view('frontend/about', ['title' => 'عن الجمعية']);
    }
    
    public function privacy(): void
    {
        $p = Database::fetch("SELECT * FROM pages WHERE slug = 'privacy' LIMIT 1");
        $this->view('frontend/page', ['title' => 'سياسة الخصوصية', 'page' => $p]);
    }
    
    public function terms(): void
    {
        $p = Database::fetch("SELECT * FROM pages WHERE slug = 'terms' LIMIT 1");
        $this->view('frontend/page', ['title' => 'الشروط والأحكام', 'page' => $p]);
    }

    /** ✅ جلب الحسابات البنكية ديناميكياً */
    public function banks(): void
    {
        $this->view('frontend/banks', [
            'title' => 'وسائل التبرع',
            'banks' => (new BankAccount())->active()
        ]);
    }
}