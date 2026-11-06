<?php
// app/Controllers/Frontend/NewsController.php
namespace App\Controllers\Frontend;

use App\Core\Controller;
use App\Models\Article;

final class NewsController extends Controller
{
    public function index(): void
    {
        $page = max(1, (int)($_GET['page'] ?? 1));
        $this->view('frontend/news', [
            'title'   => 'الأخبار',
            'data'    => (new Article())->paginate($page, 9),
        ]);
    }
    public function detail(string $slug): void
    {
        $a = (new Article())->bySlug($slug);
        if (!$a) { http_response_code(404); $this->view('errors/404', ['title'=>'غير موجود']); return; }
        $this->view('frontend/news-detail', ['title' => $a['title'], 'article' => $a]);
    }
}