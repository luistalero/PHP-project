<?php

namespace App\Views;

class View
{
    public function render(string $viewName, array $data = []): void
    {
        extract($data);

        $filePath = __DIR__ . '/' . $viewName;

        // die($filePath);

        if (!str_ends_with($filePath, '.html.php')) {
            $filePath .= '.php';
        }

        if (file_exists($filePath)) {
            require $filePath;
        } else {
            http_response_code(404);
            echo "Error: Vista no encontrada.";
        }
    }
}