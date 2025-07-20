<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Tarea</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal flex items-center justify-center min-h-screen">

    <div class="container mx-auto mt-10 p-6 bg-white rounded-lg shadow-xl max-w-md">
        <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">Editar Tarea</h1>

        <form action="/tasks/<?= htmlspecialchars($task->id) ?>/update" method="POST" class="space-y-4">
            <div>
                <label for="title" class="block text-gray-700 font-semibold mb-2">Título</label>
                <input type="text" id="title" name="title" required value="<?= htmlspecialchars($task->title) ?>" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label for="description" class="block text-gray-700 font-semibold mb-2">Descripción</label>
                <textarea id="description" name="description" rows="4" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"><?= htmlspecialchars($task->description) ?></textarea>
            </div>
            <div>
                <label for="due_date" class="block text-gray-700 font-semibold mb-2">Fecha de Vencimiento</label>
                <input type="date" id="due_date" name="due_date" required value="<?= htmlspecialchars($task->due_date) ?>" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="flex justify-between items-center">
                <a href="/tasks" class="text-blue-500 hover:underline">Cancelar</a>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-full transition duration-300 ease-in-out">
                    Actualizar Tarea
                </button>
            </div>
        </form>
    </div>

</body>
</html>