Inicio de Proyecto

--- PROBLEMAS CON LA BASE DE DATOS ---
- reset_db_final.php
```bash
CREAR ARCHIVO DE REINICIO

<?php

// Definir la ruta de la base de datos de manera explícita
const DB_PATH = __DIR__ . '/database.sqlite';

try {
    // 1. Eliminar el archivo de la base de datos si existe
    if (file_exists(DB_PATH)) {
        unlink(DB_PATH);
    }
    
    // 2. Crear una nueva conexión y la tabla 'tasks'
    $pdo = new PDO("sqlite:" . DB_PATH);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $pdo->exec("
        CREATE TABLE tasks (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            title VARCHAR(255) NOT NULL,
            description TEXT,
            due_date DATE NOT NULL,
            completed BOOLEAN NOT NULL DEFAULT 0,
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
        );
    ");

    echo "Base de datos y tabla 'tasks' creadas correctamente. Ahora puedes eliminar este archivo.";

} catch (PDOException $e) {
    http_response_code(500);
    echo "Error: " . $e->getMessage();
}
```
```bash
http://localhost:8000/reset_db_final.php
```
