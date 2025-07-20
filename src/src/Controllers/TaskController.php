<?php

namespace App\Controllers;

use App\Views\View;
use App\Models\TaskRepository;
use App\Models\Task;

class TaskController
{
    private View $view;
    private TaskRepository $taskRepository;

    public function __construct(TaskRepository $taskRepository)
    {
        $this->view = new View(__DIR__ . '/../Views');
        $this->taskRepository = $taskRepository;
    }

    /**
     * Muestra una lista de todas las tareas, con soporte para filtrado.
     */
    public function index(): void
    {
        // Obtener el filtro de la URL si existe, de lo contrario, usar 'all'
        $filter = $_GET['filter'] ?? 'all';
        $tasks = $this->taskRepository->findAll($filter);

        $this->view->render('tasks/index.html.php', ['tasks' => $tasks]);
    }

    /**
     * Muestra una lista de tareas en formato JSON para solicitudes AJAX, con soporte para filtrado.
     */
    public function getTasksJson(): void
    {
        // Obtener el filtro de la URL si existe, de lo contrario, usar 'all'
        $filter = $_GET['filter'] ?? 'all';
        $tasks = $this->taskRepository->findAll($filter);
        
        header('Content-Type: application/json');
        
        // Convertimos el array de objetos Task a un array de arrays para el JSON
        $tasksArray = array_map(function($task) {
            return $task->toArray();
        }, $tasks);

        echo json_encode(['tasks' => $tasksArray]);
    }

    /**
     * Muestra los detalles de una tarea específica.
     * @param string $id El ID de la tarea.
     */
    public function show(string $id): void
    {
        $task = $this->taskRepository->find((int)$id);

        if (!$task) {
            http_response_code(404);
            $this->view->render('404.html.php', ['title' => 'Tarea no encontrada', 'message' => 'La tarea solicitada no existe.']);
            return;
        }

        $data = [
            'title' => "Detalles de la Tarea #{$id}",
            'task' => $task
        ];
        $this->view->render('tasks/show.html.php', $data);
    }

    public function create(): void
    {
        $this->view->render('tasks/create.html.php');
    }

    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            if (isset($_SERVER['CONTENT_TYPE']) && str_contains($_SERVER['CONTENT_TYPE'], 'application/json')) {
                $json = file_get_contents('php://input');
                $data = json_decode($json, true);
            
                if ($data === null) {
                    http_response_code(400);
                    header('Content-Type: application/json');
                    echo json_encode(['success' => false, 'message' => 'Datos inválidos.']);
                    exit();
                }
            } else {
                $data = $_POST;
            }

            $title = htmlspecialchars($data['title']);
            $description = htmlspecialchars($data['description']);
            $dueDate = htmlspecialchars($data['due_date']);
    
            if (empty($title) || empty($dueDate)) {
                if (isset($_SERVER['HTTP_ACCEPT']) && str_contains($_SERVER['HTTP_ACCEPT'], 'application/json')) {
                    header('Content-Type: application/json', true, 400);
                    echo json_encode(['success' => false, 'message' => 'El título y la fecha son obligatorios.']);
                    exit();
                }
                header("Location: /tasks/create");
                exit();
            }
    
            $task = new Task($title, $description, $dueDate);
            $this->taskRepository->save($task);
    
            if (isset($_SERVER['HTTP_ACCEPT']) && str_contains($_SERVER['HTTP_ACCEPT'], 'application/json')) {
                header('Content-Type: application/json');
                echo json_encode(['success' => true, 'task' => $task->toArray(), 'message' => 'Tarea creada con éxito.']);
                exit();
            }
        }

        header("Location: /tasks");
        exit();
    }

    public function destroy(string $id): void
    {
        $this->taskRepository->delete((int)$id);
        header("Location: /tasks");
        exit();
    }

    public function edit(string $id): void
    {
        $task = $this->taskRepository->find((int)$id);

        if(!$task) {
            http_response_code(404);
            $this->view->render('404.html.php', ['title' => 'Tarea no encontrada', 'message' => 'La tarea solicitada no existe.']);
            return;
        }

        $data = ['task' => $task];
        $this->view->render('tasks/edit.html.php', $data);
    }

    public function update(string $id): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $task = $this->taskRepository->find((int)$id);
            
            if (!$task) {
                if (isset($_SERVER['HTTP_ACCEPT']) && str_contains($_SERVER['HTTP_ACCEPT'], 'application/json')) {
                    header('Content-Type: application/json', true, 404);
                    echo json_encode(['success' => false, 'message' => 'La tarea solicitada no existe.']);
                    exit();
                }
                http_response_code(404);
                $this->view->render('404.html.php', ['title' => 'Tarea no encontrada', 'message' => 'La tarea solicitada no existe.']);
                return;
            }

            if (isset($_SERVER['CONTENT_TYPE']) && str_contains($_SERVER['CONTENT_TYPE'], 'application/json')) {
                $json = file_get_contents('php://input');
                $data = json_decode($json, true);
            
                if ($data === null) {
                    http_response_code(400);
                    header('Content-Type: application/json');
                    echo json_encode(['success' => false, 'message' => 'Datos inválidos.']);
                    exit();
                }
            } else {
                $data = $_POST;
            }

            $task->title = htmlspecialchars($data['title']);
            $task->description = htmlspecialchars($data['description']);
            $task->due_date = htmlspecialchars($data['due_date']);
            
            $this->taskRepository->update($task);

            if (isset($_SERVER['HTTP_ACCEPT']) && str_contains($_SERVER['HTTP_ACCEPT'], 'application/json')) {
                header('Content-Type: application/json');
                echo json_encode(['success' => true, 'task' => $task->toArray()]);
                exit();
            }
        }

        header("Location: /tasks/{$id}");
        exit();
    }

    public function toggleComplete(string $id) : void {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $task = $this->taskRepository->find((int)$id);
    
            if ($task) {
                $task->completed = !$task->completed;
                $this->taskRepository->update($task);
            }
    
            if (isset($_SERVER['HTTP_ACCEPT']) && str_contains($_SERVER['HTTP_ACCEPT'], 'application/json')) {
                header('Content-Type: application/json');
                echo json_encode(['success' => true, 'completed' => $task->completed]);
                exit();
            }
    
            header("Location: /tasks");
            exit();
        }
    }
}