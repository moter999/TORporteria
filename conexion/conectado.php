<?php
// Habilitar la visualización de errores para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Configuración de la base de datos
$servername = "localhost";
$username = "root"; // O tu nombre de usuario de la base de datos
$password = ""; // O tu contraseña
$dbname = "porteria"; // El nombre de tu base de datos

// Configuramos el modo de error para mysqli
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    error_log("Intentando conectar a la base de datos...");
    
    // Crear la conexión
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    // Verificar la conexión
    if ($conn->connect_error) {
        error_log("Error de conexión MySQL: " . $conn->connect_error);
        throw new Exception("Error de conexión: " . $conn->connect_error);
    }
    
    // Configurar el conjunto de caracteres a UTF-8
    if (!$conn->set_charset('utf8mb4')) {
        error_log("Error al configurar charset: " . $conn->error);
        throw new Exception("Error al configurar charset: " . $conn->error);
    }

    error_log("Conexión exitosa a la base de datos");
    
} catch (Exception $e) {
    error_log("Error crítico de conexión: " . $e->getMessage());
    error_log("Trace: " . $e->getTraceAsString());
    
    // En producción, no mostrar detalles del error al usuario
    if ($_SERVER['SERVER_NAME'] === 'localhost' || $_SERVER['SERVER_NAME'] === '127.0.0.1') {
        die(json_encode([
            'success' => false,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]));
    } else {
        die(json_encode([
            'success' => false,
            'error' => 'Error de conexión a la base de datos'
        ]));
    }
}
?>