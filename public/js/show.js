document.addEventListener('DOMContentLoaded', () => {
    const toggleButton = document.getElementById('toggle-complete-btn');
    const taskStatusSpan = document.getElementById('task-status');
    const statusMessageDiv = document.getElementById('status-message');

    if (toggleButton) {
        toggleButton.addEventListener('click', async () => {
            const taskId = toggleButton.dataset.taskId;
            const isCompleted = toggleButton.dataset.completed === 'true';

            try {
                const response = await fetch(`/tasks/${taskId}/toggle-complete`, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                const result = await response.json();

                if (response.ok && result.success) {
                    const newStatus = !isCompleted;
                    
                    // Actualizar el estado visual en la página
                    taskStatusSpan.textContent = newStatus ? 'Completada' : 'Pendiente';
                    taskStatusSpan.className = newStatus 
                        ? 'text-sm font-semibold px-2 py-1 rounded-full bg-green-200 text-green-800' 
                        : 'text-sm font-semibold px-2 py-1 rounded-full bg-yellow-200 text-yellow-800';

                    // Actualizar el texto del botón
                    toggleButton.textContent = newStatus ? 'Marcar como pendiente' : 'Marcar como completada';
                    
                    // Actualizar el data attribute
                    toggleButton.dataset.completed = newStatus ? 'true' : 'false';

                    // Mostrar mensaje de éxito
                    statusMessageDiv.innerHTML = `<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline">El estado de la tarea se ha actualizado.</span>
                    </div>`;

                } else {
                    statusMessageDiv.innerHTML = `<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline">${result.message || 'Hubo un problema al actualizar el estado.'}</span>
                    </div>`;
                }

            } catch (error) {
                console.error('Error:', error);
                statusMessageDiv.innerHTML = `<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">No se pudo conectar con el servidor.</span>
                </div>`;
            }
        });
    }
});