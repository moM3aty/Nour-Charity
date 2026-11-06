<?php
// app/Controllers/Frontend/HomeController.php
namespace App\Controllers\Frontend;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Settings;
use App\Models\Article;
use App\Models\Campaign;
use App\Models\Category;
use App\Models\Partner;
use App\Models\Project;
use App\Models\HeroSlide;

final class HomeController extends Controller
{
    public function index(): void
    {
        $projects   = (new Project())->active(6);
        $featured   = (new Project())->active(4, true);
        $campaigns  = (new Campaign())->active(2);
        $categories = (new Category())->active();
        
        // جلب شركاء النجاح
        $partners   = (new Partner())->active();
        
        // جلب أحدث 3 أخبار منشورة
        $news       = (new Article())->published(3);
        
        $heroSlides = (new HeroSlide())->active();
        if (empty($heroSlides)) {
            $heroSlides = [
                [
                    'title' => setting('hero.title', 'معاً نصنع الأمل'),
                    'description' => setting('hero.description', 'عطاؤك يضيء دروبهم، شاركنا الآن في مشاريعنا الخيرية المستدامة التي تلامس احتياجات الإنسان.'),
                    'cta_text' => setting('hero.cta_primary_text', 'تبرع الآن'),
                    'cta_link' => setting('hero.cta_primary_link', '/donate'),
                    'secondary_text' => setting('hero.cta_secondary_text', 'اكتشف مشاريعنا'),
                    'secondary_link' => setting('hero.cta_secondary_link', '/projects'),
                    'image' => setting('hero.image', asset('images/hero.jpg')),
                    'subtitle' => setting('site.name_ar', 'نور مخة العالمية')
                ]
            ];
        }

        $stats = [
            'projects'      => (int) (Database::fetch("SELECT COUNT(*) AS c FROM projects WHERE status IN('active','funded','completed') AND deleted_at IS NULL")['c'] ?? 0),
            'families'      => (int) (Database::fetch("SELECT COUNT(*) AS c FROM donors")['c'] ?? 0),
            'donations_sum' => (float) (Database::fetch("SELECT COALESCE(SUM(amount),0) AS s FROM donations WHERE status='paid'")['s'] ?? 0),
            'volunteers'    => (int) (Database::fetch("SELECT COUNT(*) AS c FROM volunteer_applications WHERE status IN('accepted','reviewing')")['c'] ?? 0),
        ];

        $this->view('frontend/home', [
            'title'      => Settings::get('seo.default_title', 'الرئيسية'),
            'projects'   => $projects,
            'featured'   => $featured,
            'campaigns'  => $campaigns,
            'categories' => $categories,
            'partners'   => $partners,
            'news'       => $news,
            'stats'      => $stats,
            'heroSlides' => $heroSlides,
        ]);
    }
}