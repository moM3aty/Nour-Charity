<?php
// app/Models/PageSection.php
namespace App\Models;

use App\Core\Model;
use App\Core\Database;

final class PageSection extends Model
{
    protected string $table = 'page_sections';

    public function forPage(string $pageKey): array
    {
        $rows = Database::fetchAll(
            "SELECT * FROM page_sections WHERE page_key = ? ORDER BY sort_order, id",
            [$pageKey]
        );
        $map = [];
        foreach ($rows as $r) {
            $map[$r['section_key']] = $r;
        }
        return $map;
    }

    public function upsert(string $pageKey, string $sectionKey, array $data): void
    {
        $existing = Database::fetch(
            "SELECT id FROM page_sections WHERE page_key = ? AND section_key = ?",
            [$pageKey, $sectionKey]
        );
        $data = [
            'page_key'    => $pageKey,
            'section_key' => $sectionKey,
            'title'       => $data['title'] ?? null,
            'subtitle'    => $data['subtitle'] ?? null,
            'content'     => $data['content'] ?? null,
            'image'       => $data['image'] ?? null,
            'sort_order'  => $data['sort_order'] ?? 0,
            'is_active'   => $data['is_active'] ?? 1,
        ];
        if ($existing) {
            $this->update((int)$existing['id'], $data);
        } else {
            $this->create($data);
        }
    }
}