document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('edit-task-form');
    const messageDiv = document.getElementById('message');

    form.addEventListener('submit', async (event) => {
        event.preventDefault(); // Detener el envío normal del formulario

        // Usamos la función de utilidad para manejar el formulario
        await handleAjaxFormSubmit(form, messageDiv, () => {
            // Callback opcional: redirigir a la lista de tareas después de 3 segundos
            const taskId = form.action.split('/').slice(-2, -1)[0];
            const redirectMessage = 'La tarea se ha actualizado correctamente. Te redirigiremos en 2 segundos.';
            
            showMessage(messageDiv, redirectMessage, 'success');
            
            setTimeout(() => {
                window.location.href = `/tasks/${taskId}`;
            }, 2000);
        });
    });
});