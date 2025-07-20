<?php

namespace App\Controllers;

use App\Views\View;
use App\Models\TaskRepository; // Importamos el TaskRepository
use App\Models\Task; // Importamos la clase Task

class TaskController
{
    private View $view;
    private TaskRepository $taskRepository; 

    public function __construct(TaskRepository $taskRepository) // Ahora el constructor recibe el repositorio
    {
        // La ruta de la vista se pasa aquí al constructor de View
        $this->view = new View(__DIR__ . '/../Views'); 
        $this->taskRepository = $taskRepository; // Asignamos el repositorio
    }

    /**
     * Muestra una lista de todas las tareas.
     */
    public function index(): void
    {
        $tasks = $this->taskRepository->findAll();

        // ¡Ahora usamos la instancia de View que ya está configurada!
        $this->view->render('tasks/index.html.php', ['tasks' => $tasks]);
    }

    /**
     * Muestra los detalles de una tarea específica.
     *
     * @param string $id El ID de la tarea.
     */
    public function show(string $id): void
    {
        $task = $this->taskRepository->find((int)$id); // Obtenemos una tarea por ID

        if (!$task) {
            http_response_code(404);
            // Asegúrate de que esta vista 404.html.php exista en src/src/Views/
            $this->view->render('404.html.php', ['title' => 'Tarea no encontrada', 'message' => 'La tarea solicitada no existe.']);
            return;
        }

        $data = [
            'title' => "Detalles de la Tarea #{$id}",
            'task' => $task // Pasamos la tarea a la vista
        ];
        // Asegúrate de que esta vista tasks/show.html.php exista
        $this->view->render('tasks/show.html.php', $data);
    }

    public function create(): void
    {
        $this->view->render('tasks/create.html.php');
    }

    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            // Detectar si la solicitud es AJAX para procesar JSON
            if (isset($_SERVER['CONTENT_TYPE']) && str_contains($_SERVER['CONTENT_TYPE'], 'application/json')) {
                // Leer el cuerpo de la solicitud JSON
                $json = file_get_contents('php://input');
                $data = json_decode($json, true);
            
                // Validar que los datos se hayan decodificado correctamente
                if ($data === null) {
                    http_response_code(400);
                    header('Content-Type: application/json');
                    echo json_encode(['success' => false, 'message' => 'Datos inválidos.']);
                    exit();
                }
            } else {
                // Si no es JSON, usamos los datos de $_POST (para formularios no AJAX)
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
                // Si la solicitud es AJAX, retornamos JSON
                if (isset($_SERVER['HTTP_ACCEPT']) && str_contains($_SERVER['HTTP_ACCEPT'], 'application/json')) {
                    header('Content-Type: application/json', true, 404);
                    echo json_encode(['success' => false, 'message' => 'La tarea solicitada no existe.']);
                    exit();
                }
                http_response_code(404);
                $this->view->render('404.html.php', ['title' => 'Tarea no encontrada', 'message' => 'La tarea solicitada no existe.']);
                return;
            }

            // Detectar si la solicitud es AJAX para procesar JSON
            if (isset($_SERVER['CONTENT_TYPE']) && str_contains($_SERVER['CONTENT_TYPE'], 'application/json')) {
                // Leer el cuerpo de la solicitud JSON
                $json = file_get_contents('php://input');
                $data = json_decode($json, true);
            
                // Validar que los datos se hayan decodificado correctamente
                if ($data === null) {
                    http_response_code(400);
                    header('Content-Type: application/json');
                    echo json_encode(['success' => false, 'message' => 'Datos inválidos.']);
                    exit();
                }
            } else {
                // Si no es JSON, usamos los datos de $_POST (para formularios no AJAX)
                $data = $_POST;
            }

            // Actualizamos los campos de la tarea con los datos recibidos
            $task->title = htmlspecialchars($data['title']);
            $task->description = htmlspecialchars($data['description']);
            $task->due_date = htmlspecialchars($data['due_date']);
            
            $this->taskRepository->update($task);

            // Si la solicitud es AJAX, retornamos una respuesta JSON
            if (isset($_SERVER['HTTP_ACCEPT']) && str_contains($_SERVER['HTTP_ACCEPT'], 'application/json')) {
                header('Content-Type: application/json');
                echo json_encode(['success' => true, 'task' => $task->toArray()]);
                exit();
            }
        }

        // Si la solicitud no es AJAX (envío de formulario normal), redirigimos
        header("Location: /tasks/{$id}");
        exit();
    }

    public function toggleComplete(string $id) : void {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $task = $this->taskRepository->find((int)$id);
    
            if ($task) {
                // Toggling the completed status
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