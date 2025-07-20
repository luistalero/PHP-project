<?php

namespace App\Models;

use PDO;
use PDOException;
use App\Models\Task;

class TaskRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM tasks ORDER BY created_at DESC");
        $tasks = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $tasks[] = $this->hydrate($row);
        }
        return $tasks;
    }

    public function find(int $id): ?Task
    {
        $stmt = $this->pdo->prepare("SELECT * FROM tasks WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        return $this->hydrate($row);
    }

    public function save(Task $task): Task
    {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO tasks (title, description, due_date, completed) VALUES (:title, :description, :due_date, :completed)");
            $stmt->execute([
                'title' => $task->title,
                'description' => $task->description,
                'due_date' => $task->due_date,
                'completed' => (int)$task->completed
            ]);

            // Obtener el ID del último insert y la tarea completa con sus timestamps
            $lastInsertId = $this->pdo->lastInsertId();
            return $this->find((int)$lastInsertId);

        } catch (PDOException $e) {
            throw new PDOException("Error al guardar la tarea: " . $e->getMessage());
        }
    }

    public function update(Task $task): bool
    {
        try {
            $stmt = $this->pdo->prepare("UPDATE tasks SET title = :title, description = :description, due_date = :due_date, completed = :completed, updated_at = CURRENT_TIMESTAMP WHERE id = :id");
            return $stmt->execute([
                'id' => $task->id,
                'title' => $task->title,
                'description' => $task->description,
                'due_date' => $task->due_date,
                'completed' => (int)$task->completed
            ]);
        } catch (PDOException $e) {
            throw new PDOException("Error al actualizar la tarea: " . $e->getMessage());
        }
    }

    public function delete(int $id): bool
    {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM tasks WHERE id = :id");
            return $stmt->execute(['id' => $id]);
        } catch (PDOException $e) {
            throw new PDOException("Error al eliminar la tarea: " . $e->getMessage());
        }
    }

    private function hydrate(array $data): Task
    {
        return new Task(
            $data['title'],
            $data['description'],
            $data['due_date'],
            (int)$data['id'],
            ($data['completed'] == 1),
            $data['created_at'],
            $data['updated_at']
        );
    }
}