<?php

use App\Router\Router;
use App\Controllers\TaskController;
use App\Models\TaskRepository;

// require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/Router/Router.php';
require_once __DIR__ . '/../src/Controllers/TaskController.php';
require_once __DIR__ . '/../src/Models/TaskRepository.php';
require_once __DIR__ . '/../src/Models/Task.php';
require_once __DIR__ . '/../src/Views/View.php';

Const DB_PATH = __DIR__ . '/../database.sqlite';
$dsn = "sqlite:" . DB_PATH; 

try {
    $pdo = new PDO($dsn);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    $stmt = $pdo->query("PRAGMA table_info(tasks)");
    $columns = $stmt->fetchAll(PDO::FETCH_COLUMN, 1);

    if (!in_array('created_at', $columns)) {
        $pdo->exec("ALTER TABLE tasks ADD COLUMN created_at TIMESTAMP");
    }

    if (!in_array('updated_at', $columns)) {
        $pdo->exec("ALTER TABLE tasks ADD COLUMN updated_at TIMESTAMP");
    }

    if (!in_array('completed_at', $columns)) {
        $pdo->exec("ALTER TABLE tasks ADD COLUMN completed_at TIMESTAMP NULL");
    }
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
    $view = new App\Views\View();
    $view->render('home.html');
});

//Metodos POST
$router->addRoute('POST', '/tasks', [$taskController, 'store']);
$router->addRoute('POST', '/tasks/{id}/delete', [$taskController, 'destroy']);
$router->addRoute('POST', '/tasks/{id}/update', [$taskController, 'update']);
$router->addRoute('POST', '/tasks/{id}/toggle-complete', [$taskController, 'toggleComplete']);

//Metodos GET
$router->addRoute('GET', '/tasks', [$taskController, 'index']);
$router->addRoute('GET', '/tasks/create', [$taskController, 'create']); 
$router->addRoute('GET', '/tasks/{id}', [$taskController, 'show']);
$router->addRoute('GET', '/tasks/{id}/edit', [$taskController, 'edit']);


$router->dispatch();