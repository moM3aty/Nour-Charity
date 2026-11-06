<?php
// app/Core/Router.php
namespace App\Core;

final class Router
{
    private array $routes = ['GET' => [], 'POST' => []];

    public function get(string $path, array $handler): void
    {
        $this->routes['GET'][$this->normalize($path)] = $handler;
    }

    public function post(string $path, array $handler): void
    {
        $this->routes['POST'][$this->normalize($path)] = $handler;
    }

    private function normalize(string $path): string
    {
        $path = '/' . trim($path, '/');
        return $path === '/' ? '/' : rtrim($path, '/');
    }

    public function dispatch(string $method, string $uri): void
    {
        $path = parse_url($uri, PHP_URL_PATH) ?? '/';
        // remove base path if installed in subfolder
        $scriptDir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
        if ($scriptDir && $scriptDir !== '/' && str_starts_with($path, $scriptDir)) {
            $path = substr($path, strlen($scriptDir));
        }
        $path = $this->normalize($path === '' ? '/' : $path);

        $method = strtoupper($method);
        foreach ($this->routes[$method] ?? [] as $pattern => $handler) {
            $regex = $this->patternToRegex($pattern);
            if (preg_match($regex, $path, $matches)) {
                $params = [];
                foreach ($matches as $k => $v) {
                    if (is_string($k)) $params[$k] = urldecode($v);
                }
                [$class, $action] = $handler;
                if (!class_exists($class)) {
                    $this->notFound('Controller not found');
                    return;
                }
                $controller = new $class();
                if (!method_exists($controller, $action)) {
                    $this->notFound('Action not found');
                    return;
                }
                $controller->$action(...array_values($params));
                return;
            }
        }
        $this->notFound();
    }

    private function patternToRegex(string $pattern): string
    {
        $escaped = preg_replace('#\{([a-z_]+)\}#i', '(?P<$1>[^/]+)', $pattern);
        return '#^' . $escaped . '$#';
    }

    private function notFound(string $msg = 'Page not found'): void
    {
        http_response_code(404);
        if (is_file(BASE_PATH . '/app/Views/errors/404.php')) {
            require BASE_PATH . '/app/Views/errors/404.php';
        } else {
            echo '<!doctype html><html><head><meta charset="utf-8"><title>404</title></head><body style="font-family:sans-serif;text-align:center;padding:80px;"><h1>404</h1><p>' . htmlspecialchars($msg) . '</p><a href="/">Home</a></body></html>';
        }
    }
}