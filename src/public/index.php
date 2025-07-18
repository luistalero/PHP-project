<?php

use App\Router\Router;
use App\Controllers\TaskController;
use App\Models\TaskRepository;

require_once __DIR__ . '/../vendor/autoload.php';

$dbPath = __DIR__ . '/../database.sqlite';
$dsn = "sqlite:" . $dbPath; 

try {
    $pdo = new PDO($dsn);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    http_response_code(500);
    echo "Error de conexión a la base de datos: " . $e->getMessage();
    exit();
}

$taskRepository = new TaskRepository($pdo);

if (count($taskRepository->findAll()) === 0) {
    $task = new App\Models\Task("Mi primera tarea", "Descripción de la primera tarea", "2025-07-25");
    $taskRepository->save($task);
}

$taskController = new TaskController($taskRepository);

$router = new Router();

$router->addRoute('GET', '/', function () {
    echo "¡Bienvenido a la página de inicio!";
});

$router->addRoute('GET', '/tasks', [$taskController, 'index']); 
$router->addRoute('GET', '/tasks/{id}', [$taskController, 'show']);

$router->dispatch();