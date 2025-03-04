<?php
// Configuración de seguridad
return [
    'session' => [
        'lifetime' => 3600, // 1 hora
        'path' => '/',
        'domain' => '.tudominio.com', // Actualizar con tu dominio real
        'secure' => true,
        'httponly' => true,
        'samesite' => 'Strict'
    ],
    'database' => [
        'host' => 'localhost',
        'name' => 'porteria',
        'user' => 'app_user',
        'pass' => 'UnaContraseñaMuySegura123!', // Cambiar por una contraseña segura real
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'ssl' => true,
        'verify_cert' => true
    ],
    'security' => [
        'encryption_key' => bin2hex(random_bytes(32)),
        'csrf_token_name' => 'csrf_token',
        'max_login_attempts' => 3,
        'lockout_time' => 900, // 15 minutos
        'password_min_length' => 12,
        'require_special_chars' => true,
        'require_numbers' => true,
        'require_uppercase' => true,
        'require_lowercase' => true,
        'password_expiry_days' => 90,
        'session_regenerate_time' => 300,
        'allowed_ips' => [], // Lista de IPs permitidas para acceso administrativo
        'maintenance_mode' => false
    ],
    'logs' => [
        'enabled' => true,
        'path' => __DIR__ . '/../logs',
        'max_files' => 30,
        'level' => 'warning',
        'error_reporting' => E_ALL & ~E_DEPRECATED & ~E_STRICT,
        'log_errors' => true,
        'error_log' => __DIR__ . '/../logs/php-errors.log'
    ],
    'headers' => [
        'X-Frame-Options' => 'DENY',
        'X-XSS-Protection' => '1; mode=block',
        'X-Content-Type-Options' => 'nosniff',
        'Strict-Transport-Security' => 'max-age=31536000; includeSubDomains; preload',
        'Content-Security-Policy' => "default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdnjs.cloudflare.com; style-src 'self' 'unsafe-inline' https://cdnjs.cloudflare.com; font-src 'self' https://cdnjs.cloudflare.com; img-src 'self' data:; connect-src 'self'; frame-ancestors 'none'; form-action 'self';",
        'Permissions-Policy' => 'geolocation=(), microphone=(), camera=()',
        'Referrer-Policy' => 'strict-origin-when-cross-origin'
    ],
    'backup' => [
        'enabled' => true,
        'path' => __DIR__ . '/../backups',
        'frequency' => 'daily',
        'retention_days' => 30,
        'compress' => true
    ],
    'rate_limiting' => [
        'enabled' => true,
        'max_attempts' => 100,
        'time_window' => 3600,
        'blocked_time' => 1800
    ]
]; 