<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($title) ?></title>
</head>
<body>
    <h1>Detalles de la Tarea: <?= htmlspecialchars($task->title) ?></h1>
    <p><strong>ID:</strong> <?= htmlspecialchars($task->id) ?></p>
    <p><strong>Descripción:</strong> <?= htmlspecialchars($task->description) ?></p>
    <p><strong>Fecha de Vencimiento:</strong> <?= htmlspecialchars($task->due_date) ?></p>
    <p><strong>Estado:</strong> <?= $task->completed ? 'Completada' : 'Pendiente' ?></p>
    <a href="/tasks">Volver al listado</a>
</body>
</html>