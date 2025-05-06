<?php

// Funciones de validación
function isValidUsername($username) {
    return  !isProhibitedWord($username);
}

function isProhibitedWord($username) {
    $prohibitedWords = ['admin', 'root', 'guest'];
    return in_array(strtolower($username), $prohibitedWords);
}

// Función para generar el nombre de usuario
function generateUsername($length) {
    $characters = '*/+}{][!#$%&()=abcdefghijklmnopqrstuvwxyz0123456789_-';
    $username = '';
    for ($i = 0; $i < $length; $i++) {
        $username .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $username;
}

$generatedUsernames = [];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'generate') {
    $length = isset($_POST['length']) ? filter_var($_POST['length'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 10, 'max_range' => 64]]) : 10;
    
    $count = 5; // Generar 5 nombres de ejemplo

    if ($length === false) {
        $errors['length'] = 'La longitud debe ser un número entre 10 y 64.';
    }

    if (empty($errors)) {
        for ($i = 0; $i < $count; $i++) {
            $username = generateUsername($length);
            if (isValidUsername($username)) {
                $generatedUsernames[] = $username;
            } else {
                // En un escenario real, podrías intentar regenerar o informar un error interno
            }
        }
    }

    // Enviar la respuesta como HTML (puedes cambiar a JSON si prefieres manipular el DOM en JS)
    if (!empty($errors)) {
        echo '<div class="p-notification--warning"><p><strong>Error:</strong> Por favor, corrige los siguientes errores:</p><ul>';
        foreach ($errors as $error) {
            echo '<li>' . htmlspecialchars($error) . '</li>';
        }
        echo '</ul></div>';
    } elseif (!empty($generatedUsernames)) {
        echo '<h2>Nombres de Usuario Generados:</h2><ul class="p-list--disc">';
        foreach ($generatedUsernames as $username) {
            echo '<li>' . htmlspecialchars($username) . '</li>';
        }
        echo '</ul>';
    } else {
        echo '<p>No se generaron nombres de usuario.</p>';
    }
    exit; // Detener la ejecución para la petición AJAX
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generador de Nombres de Usuario</title>
    <link rel="stylesheet" href="https://assets.ubuntu.com/v1/vanilla-framework-version-4.23.2.min.css" />
    <link rel="stylesheet" href="css/app.css">
    <script src="js/script.js" defer></script>
</head>
<body>
    <div class="p-grid">
        <div class="p-col-6 p-offset-3">
            <h1>Generador de Nombres de Usuario</h1>
            <form id="username-form" class="p-form-aligned">
                <input type="hidden" name="action" value="generate">
                <div class="p-form-group">
                    <label for="length">Longitud/(entre 10 y 64):</label>
                    <input type="number" min="10" max="64" id="length" name="length" value="10">
                    <p class="p-form-help-text" id="length-error"></p>
                </div>
                <div class="p-form-group">
                   
                    <p class="p-form-help-text" id="style-error"></p>
                </div>
                <button type="button" class="p-button--positive" id="generate-button">Generar</button>
            </form>
            <div id="generated-results" class="mt-3">
                </div>
        </div>
    </div>
</body>
</html>