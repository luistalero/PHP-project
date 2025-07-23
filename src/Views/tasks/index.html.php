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

        <div class="flex space-x-2 mb-6">
            <button data-filter="all" class="filter-btn bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded-full hover:bg-gray-400">
                Todas
            </button>
            <button data-filter="pending" class="filter-btn bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded-full hover:bg-gray-400">
                Pendientes
            </button>
            <button data-filter="completed" class="filter-btn bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded-full hover:bg-gray-400">
                Completadas
            </button>
        </div>
        
        <ul id="tasks-list" class="space-y-4">
            </ul>
        
        <div id="loading-spinner" class="hidden text-center mt-8 text-gray-500">
            <i class="fas fa-spinner fa-spin fa-2x"></i>
        </div>
        
        <div id="no-tasks-message" class="hidden p-4 bg-gray-50 rounded-lg shadow-sm text-gray-700 mt-4">
            No hay tareas que coincidan con este filtro.
        </div>
    </div>
    <script src="/js/tasks.js"></script>

</body>
</html>