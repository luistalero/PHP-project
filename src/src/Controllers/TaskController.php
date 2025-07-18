<?php

namespace App\Controllers;

class TaskController
{
    public function index(): void
    {
        echo "<h1>Listado de Todas las tareas</h1>";
        echo "<p>Aqui se mostrarán las tareas desde la base de datos.</p>";
    }

    public function show(string $id): void
    {
        echo "<h1>Detalles de la tarea #{$id}</h1>";
        echo "<p>Aquí se mostrarán los detalles de la tarea con ID: {$id}.</p>";
    }
}