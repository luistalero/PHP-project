<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal flex items-center justify-center min-h-screen">

    <div class="container mx-auto p-6 bg-white rounded-lg shadow-xl max-w-lg">
        <div class="flex justify-between items-center mb-6">
            <h1 id="task-title" class="text-3xl font-bold text-gray-800"><?= htmlspecialchars($task->title) ?></h1>
            <div class="flex space-x-2">
                <a href="/tasks/<?= htmlspecialchars($task->id) ?>/edit" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded-full transition duration-300 ease-in-out" title="Editar tarea">
                    <i class="fas fa-pencil-alt"></i>
                </a>
                <form action="/tasks/<?= htmlspecialchars($task->id) ?>/delete" method="POST">
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-full transition duration-300 ease-in-out" title="Eliminar tarea" onclick="return confirm('¿Estás seguro de que quieres eliminar esta tarea?');">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </form>
            </div>
        </div>
        
        <div id="status-message" class="mb-4"></div>

        <div class="space-y-4">
            <div>
                <h2 class="text-lg font-semibold text-gray-700">Descripción:</h2>
                <p class="text-gray-600"><?= htmlspecialchars($task->description) ?></p>
            </div>
            <div>
                <h2 class="text-lg font-semibold text-gray-700">Estado:</h2>
                <span id="task-status" class="text-sm font-semibold px-2 py-1 rounded-full <?= $task->completed ? 'bg-green-200 text-green-800' : 'bg-yellow-200 text-yellow-800' ?>">
                    <?= $task->completed ? 'Completada' : 'Pendiente' ?>
                </span>
            </div>
            <div>
                <h2 class="text-lg font-semibold text-gray-700">Fecha de vencimiento:</h2>
                <p class="text-gray-600"><?= htmlspecialchars($task->due_date) ?></p>
            </div>
            <?php if ($task->created_at): ?>
                <div>
                    <h2 class="text-lg font-semibold text-gray-700">Creada el:</h2>
                    <p class="text-gray-600"><?= htmlspecialchars($task->created_at) ?></p>
                </div>
            <?php endif; ?>
            <?php if ($task->updated_at): ?>
                <div>
                    <h2 class="text-lg font-semibold text-gray-700">Última actualización:</h2>
                    <p class="text-gray-600"><?= htmlspecialchars($task->updated_at) ?></p>
                </div>
            <?php endif; ?>
        </div>

        <div class="mt-8 flex justify-between items-center">
            <a href="/tasks" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-full transition duration-300 ease-in-out">
                Volver a la lista
            </a>
            <button id="toggle-complete-btn" data-task-id="<?= htmlspecialchars($task->id) ?>" data-completed="<?= $task->completed ? 'true' : 'false' ?>" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-full transition duration-300 ease-in-out">
                <?= $task->completed ? 'Marcar como pendiente' : 'Marcar como completada' ?>
            </button>
        </div>
    </div>
    
    <script src="/js/show.js"></script>

</body>
</html>