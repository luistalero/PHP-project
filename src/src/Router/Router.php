<?php

namespace App\Router;

class Router
{
    private $routes = [];

    public function addRoute(string $method, string $path, $handler): void
    {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'handler' => $handler
        ];
    }

    public function dispatch(): void
    {
        $uri = strtok($_SERVER['REQUEST_URI'] ?? '/', '?');
        $method = $_SERVER['REQUEST_METHOD'];

        foreach ($this->routes as $route) {
            
            $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([a-zA-Z0-9_]+)', $route['path']);
            $pattern = str_replace('/', '\/', $pattern);
            $pattern = '/^' . $pattern . '$/';

            if ($route['method'] === $method && preg_match($pattern, $uri, $matches)) {

                array_shift($matches);
            
                $handler = $route['handler'];

                if (is_array($handler) && count($handler) === 2) {
                    $className = $handler[0];
                    $methodName = $handler[1];

                    $controller = new $className();
                    $handler = [$controller, $methodName];
                }

                call_user_func_array($handler, $matches);
                return;
            }
        }

        http_response_code(404);
        echo "404 Not Found";
    }
}