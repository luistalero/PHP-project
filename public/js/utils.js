/**
 * Muestra un mensaje de alerta en la página.
 * @param {HTMLElement} element El elemento HTML donde se mostrará el mensaje.
 * @param {string} message El texto del mensaje.
 * @param {string} type El tipo de mensaje ('success' o 'error').
 */
function showMessage(element, message, type) {
    let bgColor, textColor, strongText;

    if (type === 'success') {
        bgColor = 'bg-green-100';
        textColor = 'text-green-700';
        strongText = '¡Éxito!';
    } else {
        bgColor = 'bg-red-100';
        textColor = 'text-red-700';
        strongText = 'Error:';
    }

    element.innerHTML = `<div class="${bgColor} border border-green-400 ${textColor} px-4 py-3 rounded relative" role="alert">
        <strong class="font-bold">${strongText}</strong>
        <span class="block sm:inline">${message}</span>
    </div>`;
}

/**
 * Maneja el envío de un formulario con AJAX.
 * @param {HTMLFormElement} form El formulario que se va a manejar.
 * @param {HTMLElement} messageDiv El div donde se mostrarán los mensajes.
 * @param {Function} successCallback Callback a ejecutar en caso de éxito.
 */
async function handleAjaxFormSubmit(form, messageDiv, successCallback = () => {}) {
    messageDiv.innerHTML = ''; // Limpiar mensajes anteriores

    const formData = new FormData(form);
    const data = Object.fromEntries(formData.entries());

    try {
        const response = await fetch(form.action, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        });

        const result = await response.json();

        if (response.ok) {
            showMessage(messageDiv, result.message || 'Operación exitosa.', 'success');
            successCallback(result);
        } else {
            showMessage(messageDiv, result.message || 'Hubo un problema.', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showMessage(messageDiv, 'No se pudo conectar con el servidor.', 'error');
    }
}