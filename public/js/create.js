document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('create-task-form');
    const messageDiv = document.getElementById('message');
    const submitBtn = form.querySelector('button[type="submit"]');

    if (form) {
        form.addEventListener('submit', async (e) => {
            e.preventDefault(); // Prevenimos el envío de formulario por defecto

            messageDiv.innerHTML = ''; // Limpiamos cualquier mensaje anterior
            submitBtn.disabled = true; // Deshabilitamos el botón para evitar doble clic
            submitBtn.textContent = 'Guardando...';

            const formData = new FormData(form);
            // Creamos un objeto con los datos del formulario
            const taskData = {
                title: formData.get('title'),
                description: formData.get('description'),
                due_date: formData.get('due_date')
            };

            try {
                const response = await fetch('/tasks', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(taskData)
                });

                const result = await response.json();

                if (response.ok) {
                    // Si la tarea se creó con éxito, redirigimos a la lista de tareas
                    window.location.href = '/tasks';
                } else {
                    // Si hubo un error en la validación
                    messageDiv.innerHTML = `<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline">${result.message}</span>
                    </div>`;
                }

            } catch (error) {
                console.error('Error:', error);
                messageDiv.innerHTML = `<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">Hubo un error al conectar con el servidor.</span>
                </div>`;
            } finally {
                submitBtn.disabled = false; // Habilitamos el botón nuevamente
                submitBtn.textContent = 'Guardar Tarea';
            }
        });
    }
});