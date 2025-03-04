<?php
require_once 'auth/auth.php';
$auth = Auth::getInstance();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $csrf_token = $_POST['csrf_token'] ?? '';

    if (!$auth->validateCSRFToken($csrf_token)) {
        $error = 'Token de seguridad inválido';
    } else if ($auth->login($username, $password)) {
        header('Location: index.php');
        exit();
    } else {
        $error = 'Usuario o contraseña incorrectos';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login - Gestión de Choferes</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
    <style>
        .login-container {
            max-width: 400px;
            margin: 100px auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .login-title {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 20px;
        }
        .login-form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        .form-group {
            display: flex;
            flex-direction: column;
        }
        .form-group label {
            margin-bottom: 5px;
            color: #2c3e50;
        }
        .form-group input {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .error-message {
            color: #e74c3c;
            text-align: center;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2 class="login-title">Acceso al Sistema</h2>
        <?php if (isset($error)): ?>
            <div class="error-message"><?php echo $auth->sanitizeInput($error); ?></div>
        <?php endif; ?>
        <form method="POST" class="login-form">
            <input type="hidden" name="csrf_token" value="<?php echo $auth->generateCSRFToken(); ?>">
            <div class="form-group">
                <label for="username">Usuario:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
        </form>
    </div>
</body>
</html> 