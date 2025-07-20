<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Tareas</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">

    <div class="container mx-auto mt-10 p-6 bg-white rounded-lg shadow-xl">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Mis Tareas</h1>
            <a href="/tasks/create" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-full transition duration-300 ease-in-out">
                Crear nueva tarea
            </a>
        </div>

        <ul class="space-y-4">
            <?php if (empty($tasks)): ?>
                <li class="p-4 bg-gray-50 rounded-lg shadow-sm text-gray-700">No hay tareas aún. ¡Crea una!</li>
            <?php else: ?>
                <?php foreach ($tasks as $task): ?>
                    <li data-task-id="<?= htmlspecialchars($task->id) ?>" class="p-4 rounded-lg shadow-sm transition duration-150 ease-in-out <?= $task->completed ? 'bg-gray-200' : 'bg-gray-50' ?>">
                        <div class="flex justify-between items-start">
                            <div class="flex items-start space-x-4">
                                <form class="toggle-complete-form" action="/tasks/<?= htmlspecialchars($task->id) ?>/toggle-complete" method="POST">
                                    <input type="hidden" name="id" value="<?= htmlspecialchars($task->id) ?>">
                                    <input type="checkbox" name="completed" <?= $task->completed ? 'checked' : '' ?> class="form-checkbox h-5 w-5 text-blue-600 rounded-full cursor-pointer">
                                </form>
                                <div class="task-content <?= $task->completed ? 'line-through text-gray-500' : 'text-gray-800' ?>">
                                    <h2 class="text-xl font-semibold"><?= htmlspecialchars($task->title) ?></h2>
                                    <p class="text-sm mt-1">Vence: <?= htmlspecialchars($task->due_date) ?></p>
                                </div>
                            </div>
                            <div class="flex space-x-2 task-actions">
                                <a href="/tasks/<?= htmlspecialchars($task->id) ?>" class="text-blue-500 hover:text-blue-700 transition duration-300 ease-in-out <?= $task->completed ? 'hidden' : '' ?>" title="Ver detalles">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="/tasks/<?= htmlspecialchars($task->id) ?>/edit" class="text-yellow-500 hover:text-yellow-700 transition duration-300 ease-in-out <?= $task->completed ? 'hidden' : '' ?>" title="Editar tarea">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                                <form action="/tasks/<?= htmlspecialchars($task->id) ?>/delete" method="POST">
                                    <button type="submit" class="text-red-500 hover:text-red-700 transition duration-300 ease-in-out" title="Eliminar tarea" onclick="return confirm('¿Estás seguro de que quieres eliminar esta tarea?');">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
    </div>
    <script src="/js/tasks.js"></script>

</body>
</html>