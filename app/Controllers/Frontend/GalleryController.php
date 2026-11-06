<?php
// app/Controllers/Frontend/GalleryController.php
namespace App\Controllers\Frontend;

use App\Core\Controller;
use App\Core\Database;

final class GalleryController extends Controller
{
    public function index(): void
    {
        $page = max(1, (int)($_GET['page'] ?? 1));
        $per = 12; // عرض 12 صورة في الصفحة الواحدة
        $offset = ($page - 1) * $per;
        
        // جلب الصور فقط من مكتبة الوسائط
        $items = Database::fetchAll(
            "SELECT * FROM media WHERE mime_type LIKE 'image/%' ORDER BY created_at DESC LIMIT $per OFFSET $offset"
        );
        $total = (int) (Database::fetch("SELECT COUNT(*) AS c FROM media WHERE mime_type LIKE 'image/%'")['c'] ?? 0);
        
        $this->view('frontend/gallery', [
            'title' => 'معرض الصور ومكتبة الوسائط',
            'items' => $items,
            'pages' => (int)ceil($total / $per),
            'page'  => $page
        ]);
    }
}