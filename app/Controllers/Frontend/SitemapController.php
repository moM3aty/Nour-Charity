<?php
// app/Controllers/Frontend/SitemapController.php
namespace App\Controllers\Frontend;

use App\Core\Controller;
use App\Core\Database;

final class SitemapController extends Controller
{
    public function sitemap(): void
    {
        header('Content-Type: application/xml; charset=utf-8');
        $base = rtrim(config('app.url'), '/');

        $urls = [
            ['loc' => $base . '/', 'priority' => '1.0', 'changefreq' => 'daily'],
            ['loc' => $base . '/about', 'priority' => '0.8', 'changefreq' => 'monthly'],
            ['loc' => $base . '/projects', 'priority' => '0.9', 'changefreq' => 'daily'],
            ['loc' => $base . '/campaigns', 'priority' => '0.9', 'changefreq' => 'daily'],
            ['loc' => $base . '/news', 'priority' => '0.8', 'changefreq' => 'daily'],
            ['loc' => $base . '/volunteers', 'priority' => '0.7', 'changefreq' => 'monthly'],
            ['loc' => $base . '/contact', 'priority' => '0.6', 'changefreq' => 'yearly'],
        ];

        foreach (Database::fetchAll("SELECT slug, updated_at FROM projects WHERE deleted_at IS NULL AND status='active'") as $p) {
            $urls[] = ['loc' => $base . '/projects/' . $p['slug'], 'priority' => '0.7', 'changefreq' => 'weekly', 'lastmod' => $p['updated_at'] ?? null];
        }
        foreach (Database::fetchAll("SELECT slug, updated_at FROM campaigns WHERE is_active=1") as $c) {
            $urls[] = ['loc' => $base . '/campaigns/' . $c['slug'], 'priority' => '0.7', 'changefreq' => 'weekly', 'lastmod' => $c['updated_at'] ?? null];
        }
        foreach (Database::fetchAll("SELECT slug, updated_at FROM articles WHERE status='published' AND deleted_at IS NULL") as $a) {
            $urls[] = ['loc' => $base . '/news/' . $a['slug'], 'priority' => '0.6', 'changefreq' => 'monthly', 'lastmod' => $a['updated_at'] ?? null];
        }

        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        foreach ($urls as $u) {
            echo '  <url>' . "\n";
            echo '    <loc>' . htmlspecialchars($u['loc']) . '</loc>' . "\n";
            if (!empty($u['lastmod'])) echo '    <lastmod>' . date('c', strtotime($u['lastmod'])) . '</lastmod>' . "\n";
            echo '    <changefreq>' . $u['changefreq'] . '</changefreq>' . "\n";
            echo '    <priority>' . $u['priority'] . '</priority>' . "\n";
            echo '  </url>' . "\n";
        }
        echo '</urlset>';
        exit;
    }
}