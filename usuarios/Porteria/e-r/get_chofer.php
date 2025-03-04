<?php
require_once 'auth/auth.php';
$auth = Auth::getInstance();

// Verificar autenticación
$auth->checkAuth();

// Configurar headers de seguridad
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');
header('Content-Type: application/json; charset=utf-8');

try {
    // Validar y sanitizar el ID
    if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
        throw new Exception('ID inválido o no proporcionado');
    }

    $id = filter_var($_GET['id'], FILTER_VALIDATE_INT);
    if ($id === false) {
        throw new Exception('ID debe ser un número entero');
    }

    // Preparar y ejecutar la consulta
    $stmt = $auth->getDB()->prepare("SELECT * FROM data_chofer WHERE id = ?");
    if (!$stmt) {
        throw new Exception('Error al preparar la consulta');
    }

    $stmt->bind_param("i", $id);
    if (!$stmt->execute()) {
        throw new Exception('Error al ejecutar la consulta');
    }

    $result = $stmt->get_result();
    if ($result->num_rows === 0) {
        throw new Exception('No se encontraron datos para el ID proporcionado');
    }

    $data = $result->fetch_assoc();
    
    // Sanitizar datos antes de enviarlos
    $sanitizedData = $auth->sanitizeInput($data);

    echo json_encode([
        'success' => true,
        'data' => $sanitizedData
    ]);

} catch (Exception $e) {
    // Log del error para el administrador
    error_log("Error en get_chofer.php: " . $e->getMessage());
    
    // Respuesta genérica para el usuario
    echo json_encode([
        'success' => false,
        'message' => 'Error al procesar la solicitud'
    ]);
}

// Cerrar la conexión y liberar recursos
if (isset($stmt)) {
    $stmt->close();
}
if (isset($result)) {
    $result->free();
}
?>