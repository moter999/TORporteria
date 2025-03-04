<?php
require_once '../config/security.php';
$config = require '../config/security.php';

try {
    $conn = new mysqli(
        $config['database']['host'],
        $config['database']['user'],
        $config['database']['pass'],
        $config['database']['name']
    );

    if ($conn->connect_error) {
        throw new Exception("Error de conexión: " . $conn->connect_error);
    }

    // Crear tabla de usuarios
    $sql = "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        email VARCHAR(100) UNIQUE NOT NULL,
        active TINYINT(1) DEFAULT 1,
        last_login DATETIME,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        failed_attempts INT DEFAULT 0,
        locked_until DATETIME NULL
    )";

    if (!$conn->query($sql)) {
        throw new Exception("Error al crear la tabla: " . $conn->error);
    }

    // Crear usuario administrador por defecto
    $username = 'admin';
    $password = password_hash('admin123', PASSWORD_ARGON2ID);
    $email = 'admin@example.com';

    $stmt = $conn->prepare("INSERT IGNORE INTO users (username, password, email) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $password, $email);
    
    if (!$stmt->execute()) {
        throw new Exception("Error al crear usuario admin: " . $stmt->error);
    }

    echo "Tabla de usuarios y usuario administrador creados exitosamente\n";
    echo "Usuario: admin\n";
    echo "Contraseña: admin123\n";
    echo "Por favor, cambie la contraseña después del primer inicio de sesión\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
} finally {
    if (isset($stmt)) {
        $stmt->close();
    }
    if (isset($conn)) {
        $conn->close();
    }
} 