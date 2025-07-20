
document.querySelectorAll('.toggle-complete-form').forEach(form => {
    form.addEventListener('change', async (e) => {
        e.preventDefault();

        const taskId = form.querySelector('input[name="id"]').value;
        const isChecked = form.querySelector('input[name="completed"]').checked;

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ completed: isChecked })
            });

            if (response.ok) {
                const taskItem = form.closest('li');
                const taskContent = taskItem.querySelector('.task-content');
                const taskActions = taskItem.querySelector('.task-actions');

                if (isChecked) {
                    taskItem.classList.add('bg-gray-200');
                    taskItem.classList.remove('bg-gray-50');
                    taskContent.classList.add('line-through', 'text-gray-500');
                    taskContent.classList.remove('text-gray-800');
                    taskActions.querySelector('[title="Ver detalles"]').classList.add('hidden');
                    taskActions.querySelector('[title="Editar tarea"]').classList.add('hidden');
                } else {
                    taskItem.classList.remove('bg-gray-200');
                    taskItem.classList.add('bg-gray-50');
                    taskContent.classList.remove('line-through', 'text-gray-500');
                    taskContent.classList.add('text-gray-800');
                    taskActions.querySelector('[title="Ver detalles"]').classList.remove('hidden');
                    taskActions.querySelector('[title="Editar tarea"]').classList.remove('hidden');
                }
            }
        } catch (error) {
            console.error('Error:', error);
        }
    });
});