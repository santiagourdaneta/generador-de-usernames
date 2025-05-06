document.addEventListener('DOMContentLoaded', function() {
    const generateButton = document.getElementById('generate-button');
    const usernameForm = document.getElementById('username-form');
    const resultsContainer = document.getElementById('generated-results');

    generateButton.addEventListener('click', function(event) {
        event.preventDefault(); // Evitar la recarga de la página

        const formData = new FormData(usernameForm);

        fetch(window.location.href, { // Enviar al mismo script
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest' // Indicar que es una petición AJAX
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.text();
        })
        .then(data => {
            resultsContainer.innerHTML = data; // Mostrar los resultados HTML
        })
        .catch(error => {
            console.error('Error al generar nombres de usuario:', error);
            resultsContainer.innerHTML = '<div class="p-notification--error"><p>Error al generar los nombres de usuario.</p></div>';
        });
    });
});