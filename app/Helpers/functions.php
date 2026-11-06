<?php
// app/Helpers/functions.php
declare(strict_types=1);

use App\Core\CSRF;
use App\Core\Settings;

if (!function_exists('config')) {
    function config(string $key, mixed $default = null): mixed {
        static $cfg = null;
        if ($cfg === null) $cfg = require BASE_PATH . '/config/config.php';
        $parts = explode('.', $key);
        $v = $cfg;
        foreach ($parts as $p) {
            if (!is_array($v) || !array_key_exists($p, $v)) return $default;
            $v = $v[$p];
        }
        return $v;
    }
}

if (!function_exists('e')) {
    function e(mixed $v): string { return htmlspecialchars((string)$v, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
}

if (!function_exists('url')) {
    function url(string $path = ''): string {
        $base = config('app.url');
        if ($path === '') return $base . '/';
        if (preg_match('#^https?://#i', $path)) return $path;
        return $base . '/' . ltrim($path, '/');
    }
}

if (!function_exists('asset')) {
    function asset(string $path): string { return url('assets/' . ltrim($path, '/')); }
}

if (!function_exists('csrf_token')) {
    function csrf_token(): string { return CSRF::token(); }
}

if (!function_exists('csrf_field')) {
    function csrf_field(): string { return CSRF::field(); }
}

if (!function_exists('money')) {
    function money(float|int|string $amount, ?string $currency = null): string {
        $currency ??= config('currency.symbol');
        return number_format((float)$amount, 2, '.', ',') . ' ' . $currency;
    }
}

if (!function_exists('dt')) {
    function dt(?string $datetime, string $format = 'Y-m-d'): string {
        if (!$datetime) return '';
        try { return (new DateTime($datetime))->format($format); }
        catch (\Throwable) { return ''; }
    }
}

if (!function_exists('old')) {
    function old(string $key, mixed $default = ''): mixed {
        $v = $_SESSION['_old'][$key] ?? $default;
        return $v;
    }
}

if (!function_exists('setting')) {
    function setting(string $key, mixed $default = null): mixed {
        return Settings::get($key, $default);
    }
}

if (!function_exists('slugify')) {
    function slugify(string $text): string {
        $text = trim($text);
        // دعم أفضل للحروف العربية والإنجليزية
        $text = preg_replace('/[^\p{L}\p{N}\s\-_]+/u', '', $text) ?? '';
        $text = preg_replace('/[\s\-_]+/u', '-', $text) ?? '';
        return trim($text, '-') ?: 'item-' . substr(bin2hex(random_bytes(4)), 0, 8);
    }
}

if (!function_exists('generate_ref')) {
    function generate_ref(string $prefix = 'DON'): string {
        return $prefix . '-' . date('Ymd') . '-' . strtoupper(bin2hex(random_bytes(4)));
    }
}

if (!function_exists('is_admin_route')) {
    function is_admin_route(): bool {
        return str_starts_with(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/', '/admin');
    }
}

if (!function_exists('sanitize_html')) {
    function sanitize_html(string $html): string {
        if (trim($html) === '') return '';
        
        // إصلاح جذري للحروف العربية مع DOMDocument
        $html = mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');
        
        $doc = new \DOMDocument('1.0', 'UTF-8');
        $prev = libxml_use_internal_errors(true);
        
        // تحميل الكود مع الجذر لضمان عدم فقده
        $doc->loadHTML('<div>' . $html . '</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();
        libxml_use_internal_errors($prev);
        
        // مسح العناصر الخطرة (JavaScript و iframes الغير موثوقة)
        $dangerous = ['script','object','embed','meta','link','base'];
        foreach ($dangerous as $tag) {
            $nodes = $doc->getElementsByTagName($tag);
            while ($nodes->length > 0) {
                $n = $nodes->item(0);
                if ($n && $n->parentNode) $n->parentNode->removeChild($n);
            }
        }
        
        // مسح iframes غير يوتيوب وفيميو
        $iframes = $doc->getElementsByTagName('iframe');
        $iframesToRemove = [];
        foreach ($iframes as $iframe) {
            $src = (string) $iframe->getAttribute('src');
            if (!preg_match('/(youtube\.com|youtu\.be|vimeo\.com)/i', $src)) {
                $iframesToRemove[] = $iframe;
            }
        }
        foreach ($iframesToRemove as $iframe) {
            if ($iframe->parentNode) $iframe->parentNode->removeChild($iframe);
        }

        // تنظيف السمات الخطرة مثل onmouseover, onclick ...
        $xpath = new \DOMXPath($doc);
        foreach ($xpath->query('//*') as $node) {
            if (!$node->attributes) continue;
            
            // تحويل السمات لمصفوفة لمنع الأخطاء أثناء الحذف
            $attrs = [];
            foreach ($node->attributes as $attr) {
                $attrs[] = $attr;
            }
            
            foreach ($attrs as $attr) {
                $name = strtolower($attr->name);
                $val = (string) $attr->value;
                if (str_starts_with($name, 'on')) { 
                    $node->removeAttribute($attr->name); 
                    continue; 
                }
                if (in_array($name, ['href','src','action','formaction','xlink:href'], true)) {
                    if (preg_match('/^\s*(javascript|vbscript|data)\s*:/i', $val)) {
                        $node->removeAttribute($attr->name);
                    }
                }
            }
        }
        
        // استخراج المحتوى النظيف
        $out = '';
        $root = $doc->documentElement;
        if ($root) {
            foreach ($root->childNodes as $child) {
                $out .= $doc->saveHTML($child);
            }
        }
        
        // إعادة تحويل الكود ليظهر كـ UTF-8 سليم في الداتابيز
        $out = mb_convert_encoding($out, 'UTF-8', 'HTML-ENTITIES');
        
        return trim($out);
    }
}

if (!function_exists('store_private_upload')) {
    function store_private_upload(array $file, string $subdir, array $allowedMimes): ?string {
        if (empty($file['tmp_name']) || $file['error'] !== UPLOAD_ERR_OK) return null;
        $maxSize = (int) (config('uploads.max_size') ?? 8388608);
        if ($file['size'] > $maxSize) return null;
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file['tmp_name']) ?: '';
        if (!in_array($mime, $allowedMimes, true)) return null;
        $ext = match($mime) {
            'image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp',
            'image/svg+xml' => 'svg', 'application/pdf' => 'pdf',
            'application/msword' => 'doc',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
            default => 'bin',
        };
        $private = config('uploads.private_path') ?? (BASE_PATH . '/storage/private');
        $dir = $private . '/' . trim($subdir, '/') . '/' . date('Y/m');
        if (!is_dir($dir) && !@mkdir($dir, 0755, true) && !is_dir($dir)) return null;
        $name = date('YmdHis') . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
        $dest = $dir . '/' . $name;
        if (!move_uploaded_file($file['tmp_name'], $dest)) return null;
        $rel = str_replace($private, '', $dest);
        return ltrim(str_replace('\\', '/', $rel), '/');
    }
}

if (!function_exists('store_public_upload')) {
    function store_public_upload(array $file, string $subdir, array $allowedMimes): ?string {
        if (empty($file['tmp_name']) || $file['error'] !== UPLOAD_ERR_OK) return null;
        $maxSize = (int) (config('uploads.max_size') ?? 8388608);
        if ($file['size'] > $maxSize) return null;
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file['tmp_name']) ?: '';
        if (!in_array($mime, $allowedMimes, true)) return null;
        $ext = match($mime) {
            'image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp',
            'image/svg+xml' => 'svg', 'application/pdf' => 'pdf', default => 'bin',
        };
        $public = config('uploads.public_path') ?? (BASE_PATH . '/uploads');
        $dir = $public . '/' . trim($subdir, '/') . '/' . date('Y/m');
        if (!is_dir($dir) && !@mkdir($dir, 0755, true) && !is_dir($dir)) return null;
        $name = date('YmdHis') . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
        $dest = $dir . '/' . $name;
        if (!move_uploaded_file($file['tmp_name'], $dest)) return null;
        $rel = str_replace($public, '', $dest);
        return rtrim(config('uploads.public_url') ?? '/uploads', '/') . str_replace('\\', '/', $rel);
    }
}

if (!function_exists('delete_private_file')) {
    function delete_private_file(string $relative): bool {
        $private = config('uploads.private_path') ?? (BASE_PATH . '/storage/private');
        $path = $private . '/' . ltrim($relative, '/');
        return is_file($path) && @unlink($path);
    }
}