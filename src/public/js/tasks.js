document.addEventListener('DOMContentLoaded', () => {
    const tasksList = document.getElementById('tasks-list');
    const filterButtons = document.querySelectorAll('.filter-btn');
    const loadingSpinner = document.getElementById('loading-spinner');
    const noTasksMessage = document.getElementById('no-tasks-message');
    let currentFilter = 'all'; // Variable para rastrear el filtro actual

    // Función para renderizar el HTML de una sola tarea
    function createTaskHtml(task) {
        const isCompleted = task.completed; 
        const completedClass = isCompleted ? 'bg-gray-200' : 'bg-gray-50';
        const textClass = isCompleted ? 'line-through text-gray-500' : 'text-gray-800';
        const editHiddenClass = isCompleted ? 'hidden' : '';
        const completedAtText = task.completed_at ? `Completada: ${new Date(task.completed_at).toLocaleString()}` : '';

        return `
            <li data-task-id="${task.id}" class="p-4 rounded-lg shadow-sm transition duration-150 ease-in-out ${completedClass}">
                <div class="flex justify-between items-start">
                    <div class="flex items-start space-x-4">
                        <form class="toggle-complete-form" action="/tasks/${task.id}/toggle-complete" method="POST">
                            <input type="hidden" name="id" value="${task.id}">
                            <input type="checkbox" name="completed" ${isCompleted ? 'checked' : ''} class="form-checkbox h-5 w-5 text-blue-600 rounded-full cursor-pointer">
                        </form>
                        <div class="task-content ${textClass}">
                            <h2 class="text-xl font-semibold">${task.title}</h2>
                            <p class="text-sm mt-1">Vence: ${task.due_date}</p>
                            ${completedAtText ? `<p class="text-xs mt-1 text-gray-400">${completedAtText}</p>` : ''}
                        </div>
                    </div>
                    <div class="flex space-x-2 task-actions">
                        <a href="/tasks/${task.id}" class="text-blue-500 hover:text-blue-700 transition duration-300 ease-in-out" title="Ver detalles">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="/tasks/${task.id}/edit" class="edit-link text-yellow-500 hover:text-yellow-700 transition duration-300 ease-in-out ${editHiddenClass}" title="Editar tarea">
                            <i class="fas fa-pencil-alt"></i>
                        </a>
                        <form class="delete-task-form" action="/tasks/${task.id}/delete" method="POST">
                            <button type="submit" class="text-red-500 hover:text-red-700 transition duration-300 ease-in-out" title="Eliminar tarea">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </li>
        `;
    }

    // Función principal para obtener y renderizar tareas
    async function fetchAndRenderTasks(filter = 'all') {
        currentFilter = filter;
        tasksList.innerHTML = '';
        noTasksMessage.classList.add('hidden');
        loadingSpinner.classList.remove('hidden');

        try {
            const response = await fetch(`/api/tasks?filter=${filter}`);
            if (!response.ok) {
                throw new Error('Error al obtener las tareas');
            }
            const data = await response.json();
            
            loadingSpinner.classList.add('hidden');
            
            if (data.tasks.length > 0) {
                const tasksHtml = data.tasks.map(createTaskHtml).join('');
                tasksList.innerHTML = tasksHtml;
            } else {
                noTasksMessage.classList.remove('hidden');
            }

        } catch (error) {
            console.error('Error:', error);
            loadingSpinner.classList.add('hidden');
            noTasksMessage.classList.remove('hidden');
            noTasksMessage.textContent = 'Hubo un error al cargar las tareas.';
        }
    }

    // Eventos para los botones de filtro
    filterButtons.forEach(button => {
        button.addEventListener('click', () => {
            const filter = button.dataset.filter;
            filterButtons.forEach(btn => btn.classList.remove('bg-blue-500', 'text-white'));
            button.classList.add('bg-blue-500', 'text-white');
            fetchAndRenderTasks(filter);
        });
    });

    // Delegación de eventos para los formularios (eliminar y completar)
    tasksList.addEventListener('submit', async (e) => {
        const form = e.target.closest('form');
        if (!form) return;

        e.preventDefault();

        if (form.classList.contains('delete-task-form')) {
            const userConfirmed = confirm('¿Estás seguro de que quieres eliminar esta tarea?');
            if (!userConfirmed) return;

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: { 'X-HTTP-Method-Override': 'DELETE' },
                });
                if (response.ok) {
                    // Actualizamos la lista después de eliminar
                    fetchAndRenderTasks(currentFilter);
                } else {
                    alert('Hubo un problema al eliminar la tarea.');
                }
            } catch (error) {
                console.error('Error:', error);
            }
        }
    });

    tasksList.addEventListener('change', async (e) => {
        const form = e.target.closest('form');
        if (!form) return;

        if (form.classList.contains('toggle-complete-form')) {
            const isChecked = e.target.checked;
            
            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ completed: isChecked })
                });

                if (response.ok) {
                    // Actualizamos la lista después de completar/descompletar
                    fetchAndRenderTasks(currentFilter);
                }
            } catch (error) {
                console.error('Error:', error);
            }
        }
    });

    // Llamada inicial para cargar todas las tareas al iniciar la página
    fetchAndRenderTasks('all');
});