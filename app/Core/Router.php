<?php

namespace App\Core;

/**
 * Simple pattern based router.
 *
 * Routes are registered as:  $router->get('products/{slug}', [ProductController::class, 'show']);
 * With URL rewriting disabled the same paths are served through ?p=/products/...
 */
class Router
{
    /** @var array<int, array{method:string, pattern:string, regex:string, handler:array}> */
    private array $routes = [];

    public function get(string $pattern, array $handler): void
    {
        $this->add('GET', $pattern, $handler);
    }

    public function post(string $pattern, array $handler): void
    {
        $this->add('POST', $pattern, $handler);
    }

    public function add(string $method, string $pattern, array $handler): void
    {
        $regex = '#^' . preg_replace('#\{([a-zA-Z_]+)\}#', '(?P<$1>[^/]+)', trim($pattern, '/')) . '$#u';
        $this->routes[] = [
            'method'  => strtoupper($method),
            'pattern' => trim($pattern, '/'),
            'regex'   => $regex,
            'handler' => $handler,
        ];
    }

    public function dispatch(string $method, string $path): void
    {
        $method = strtoupper($method);
        $path = trim(rawurldecode($path), '/');
        $allowed = [];

        foreach ($this->routes as $route) {
            if (!preg_match($route['regex'], $path, $matches)) {
                continue;
            }
            if ($route['method'] !== $method) {
                $allowed[] = $route['method'];
                continue;
            }

            $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
            [$class, $action] = $route['handler'];
            if (!class_exists($class) || !method_exists($class, $action)) {
                throw new \RuntimeException("Route handler not found: $class@$action");
            }
            $controller = new $class();
            $controller->$action(...array_values($params));
            return;
        }

        if ($allowed) {
            http_response_code(405);
            header('Allow: ' . implode(', ', $allowed));
        } else {
            http_response_code(404);
        }
        not_found_page();
    }
}
