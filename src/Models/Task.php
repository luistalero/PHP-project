<?php

namespace App\Models;

class Task
{
    public ?int $id = null;
    public string $title;
    public string $description;
    public string $due_date; // Formato YYYY-MM-DD
    public bool $completed = false;
    public ?string $created_at = null;
    public ?string $updated_at = null;
    public ?string $completed_at = null;

    public function __construct(string $title, string $description, string $dueDate, ?int $id = null, bool $completed = false,  ?string $created_at = null, ?string $updated_at = null, ?string $completed_at = null)
    {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->due_date = $dueDate;
        $this->completed = $completed;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
        $this->completed_at = $completed_at;
    }

    public function markAsCompleted(): void
    {
        $this->completed = true;
    }
    
    public function toArray(): array
{
    return [
        'id' => $this->id,
        'title' => $this->title,
        'description' => $this->description,
        'due_date' => $this->due_date,
        'completed' => (bool)$this->completed, // Convertir a booleano
        'created_at' => $this->created_at,
        'updated_at' => $this->updated_at,
        'completed_at' => $this->completed_at,
    ];
}
}