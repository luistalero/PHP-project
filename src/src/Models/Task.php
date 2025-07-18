<?php

namespace App\Models;

class Task
{
    public ?int $id = null;
    public string $title;
    public string $description;
    public string $due_date; // Formato YYYY-MM-DD
    public bool $completed = false;

    public function __construct(string $title, string $description, string $dueDate, ?int $id = null, bool $completed = false)
    {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->due_date = $dueDate;
        $this->completed = $completed;
    }

    public function markAsCompleted(): void
    {
        $this->completed = true;
    }
}