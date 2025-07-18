<?php

namespace App\Controllers;

use App\Views\View;
use App\Models\TaskRepository; // Importamos el TaskRepository
use App\Models\Task; // Importamos la clase Task

class TaskController
{
    private View $view;
    private TaskRepository $taskRepository; // Nueva propiedad para el repositorio

    public function __construct(TaskRepository $taskRepository) // Ahora el constructor recibe el repositorio
    {
        $this->view = new View(__DIR__ . '/../Views'); // La ruta de la vista sigue siendo la misma
        $this->taskRepository = $taskRepository; // Asignamos el repositorio
    }

    /**
     * Muestra una lista de todas las tareas.
     */
    public function index(): void
    {
        $tasks = $this->taskRepository->findAll(); // Obtenemos las tareas del repositorio

        $data = [
            'title' => 'Listado de Tareas',
            'heading' => 'Listado de todas las tareas',
            'tasks' => $tasks // Pasamos las tareas a la vista
        ];
        $this->view->render('tasks/index', $data);
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
            $this->view->render('404', ['title' => 'Tarea no encontrada', 'message' => 'La tarea solicitada no existe.']);
            return;
        }

        $data = [
            'title' => "Detalles de la Tarea #{$id}",
            'task' => $task // Pasamos la tarea a la vista
        ];
        $this->view->render('tasks/show', $data);
    }
}