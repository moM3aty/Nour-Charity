<?php
// app/Core/View.php
namespace App\Core;

final class View
{
    public static function render(string $tpl, array $viewData = [], string $layout = 'frontend'): void
    {
        $tplFile = BASE_PATH . '/app/Views/' . str_replace('.', '/', $tpl) . '.php';
        $layoutFile = BASE_PATH . '/app/Views/layouts/' . $layout . '.php';

        if (!is_file($tplFile)) {
            http_response_code(500);
            exit("View not found: $tpl");
        }

        extract($viewData, EXTR_SKIP);

        ob_start();
        require $tplFile;
        $content = ob_get_clean();

        if (is_file($layoutFile)) {
            require $layoutFile;
        } else {
            echo $content;
        }
    }

    public static function partial(string $tpl, array $viewData = []): void
    {
        $file = BASE_PATH . '/app/Views/' . str_replace('.', '/', $tpl) . '.php';
        if (is_file($file)) {
            extract($viewData, EXTR_SKIP);
            require $file;
        }
    }
}