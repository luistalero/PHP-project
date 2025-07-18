<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($title) ?></title>
</head>
<body>
    <h1><?= htmlspecialchars($heading) ?></h1>
    <p>Esta es la vista de todas las tareas.</p>

    <h2>Mis Tareas</h2>
    <?php if (empty($tasks)): ?>
        <p>No hay tareas aún. ¡Crea una!</p>
    <?php else: ?>
        <ul>
            <?php foreach ($tasks as $task): ?>
                <li>
                    <strong><?= htmlspecialchars($task->title) ?></strong> (Vence: <?= htmlspecialchars($task->due_date) ?>)
                    - <?= $task->completed ? 'Completada' : 'Pendiente' ?>
                    <br>
                    <small><?= htmlspecialchars($task->description) ?></small>
                    <a href="/tasks/<?= htmlspecialchars($task->id) ?>">Ver Detalles</a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</body>
</html>