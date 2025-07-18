<?php

use App\Router\Router;
use App\Controllers\TaskController;

require_once __DIR__ . '/../vendor/autoload.php';

$router = new Router();

$router->addRoute('GET', '/', function () {
    echo "¡Bienvenido a la pagina de inicio!";
});

$router->addRoute('GET', '/tasks', [TaskController::class, 'index']);

$router->addRoute('GET', '/tasks/{id}', [TaskController::class, 'show']);

$router->dispatch();