<?php
class ErrorHandler {
    public static function handleError($message, $type = 'error') {
        $_SESSION['message'] = $message;
        $_SESSION['message_type'] = $type;
        
        // Log del error para administradores
        error_log("Error en la aplicación: " . $message);
        
        // Redireccionar a la página principal
        header('Location: index.php');
        exit();
    }

    public static function showMessage() {
        if (isset($_SESSION['message'])) {
            $type = isset($_SESSION['message_type']) ? $_SESSION['message_type'] : 'error';
            $message = $_SESSION['message'];
            unset($_SESSION['message']);
            unset($_SESSION['message_type']);
            return ['message' => $message, 'type' => $type];
        }
        return null;
    }
}
?> 