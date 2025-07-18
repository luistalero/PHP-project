<?php 

namespace App\Views;

class View
{
    public function render(string $templatePath, array $data = []): void
    {
        extract($data);

        require_once __DIR__ . '/' . $templatePath . '.php';
    }
}