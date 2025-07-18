<?php

namespace App\Models;

use PDO;
use PDOException;

class TaskRepository
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
        $this->createTable(); // Aseguramos que la tabla exista al inicializar
    }

    /**
     * Creates the tasks table if it doesn't exist.
     */
    private function createTable(): void
    {
        $sql = "
            CREATE TABLE IF NOT EXISTS tasks (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                title TEXT NOT NULL,
                description TEXT,
                due_date TEXT NOT NULL,
                completed INTEGER DEFAULT 0
            );
        ";
        try {
            $this->db->exec($sql);
        } catch (PDOException $e) {
            // En un entorno de producción, esto debería ser un log, no un echo
            error_log("Error creating tasks table: " . $e->getMessage());
            // Podríamos lanzar una excepción personalizada aquí
            throw new \RuntimeException("Could not create tasks table.", 0, $e);
        }
    }

    /**
     * Finds all tasks.
     *
     * @return Task[] An array of Task objects.
     */
    public function findAll(): array
    {
        $stmt = $this->db->query("SELECT * FROM tasks ORDER BY id DESC");
        $tasksData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $tasks = [];
        foreach ($tasksData as $data) {
            $tasks[] = new Task(
                $data['title'],
                $data['description'],
                $data['due_date'],
                $data['id'],
                (bool)$data['completed']
            );
        }
        return $tasks;
    }

    /**
     * Finds a task by its ID.
     *
     * @param int $id The task ID.
     * @return Task|null The Task object if found, null otherwise.
     */
    public function find(int $id): ?Task
    {
        $stmt = $this->db->prepare("SELECT * FROM tasks WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) {
            return null;
        }

        return new Task(
            $data['title'],
            $data['description'],
            $data['due_date'],
            $data['id'],
            (bool)$data['completed']
        );
    }

    /**
     * Saves a new task to the database.
     *
     * @param Task $task The Task object to save.
     * @return bool True on success, false on failure.
     */
    public function save(Task $task): bool
    {
        $sql = "INSERT INTO tasks (title, description, due_date, completed) VALUES (:title, :description, :due_date, :completed)";
        $stmt = $this->db->prepare($sql);

        $stmt->bindParam(':title', $task->title);
        $stmt->bindParam(':description', $task->description);
        $stmt->bindParam(':due_date', $task->due_date);
        $completedInt = (int)$task->completed; // SQLite stores booleans as integers
        $stmt->bindParam(':completed', $completedInt, PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * Updates an existing task in the database.
     *
     * @param Task $task The Task object to update.
     * @return bool True on success, false on failure.
     */
    public function update(Task $task): bool
    {
        if ($task->id === null) {
            return false; // Cannot update a task without an ID
        }

        $sql = "UPDATE tasks SET title = :title, description = :description, due_date = :due_date, completed = :completed WHERE id = :id";
        $stmt = $this->db->prepare($sql);

        $stmt->bindParam(':title', $task->title);
        $stmt->bindParam(':description', $task->description);
        $stmt->bindParam(':due_date', $task->due_date);
        $completedInt = (int)$task->completed;
        $stmt->bindParam(':completed', $completedInt, PDO::PARAM_INT);
        $stmt->bindParam(':id', $task->id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * Deletes a task from the database.
     *
     * @param int $id The ID of the task to delete.
     * @return bool True on success, false on failure.
     */
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM tasks WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}