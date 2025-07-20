document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('create-task-form');
    const messageDiv = document.getElementById('message');

    form.addEventListener('submit', async (event) => {
        event.preventDefault(); 

        await handleAjaxFormSubmit(form, messageDiv, () => {
             window.location.href = `/tasks`;
        });
    });
});