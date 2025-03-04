<?php
require_once __DIR__ . '/../utils/Logger.php';
require_once __DIR__ . '/../auth/auth.php';

class SecurityCheck {
    private $auth;
    private $config;
    
    public function __construct() {
        $this->auth = Auth::getInstance();
        $this->config = require __DIR__ . '/../config/security.php';
        Logger::init();
    }
    
    public function runChecks() {
        Logger::info('Iniciando verificación de seguridad diaria');
        
        $this->checkFilePermissions();
        $this->checkLogFiles();
        $this->checkBackups();
        $this->checkDatabaseConnection();
        $this->checkSSLCertificate();
        $this->checkErrorLogs();
        
        Logger::info('Verificación de seguridad completada');
    }
    
    private function checkFilePermissions() {
        $sensitiveDirs = ['config', 'logs', 'backups'];
        foreach ($sensitiveDirs as $dir) {
            $path = __DIR__ . '/../' . $dir;
            if (is_readable($path) && !is_writable($path)) {
                Logger::warning("Directorio $dir tiene permisos incorrectos");
            }
        }
    }
    
    private function checkLogFiles() {
        $logPath = $this->config['logs']['path'];
        $files = glob($logPath . '/*.log');
        
        foreach ($files as $file) {
            if (filesize($file) > 50000000) { // 50MB
                Logger::warning("Archivo de log demasiado grande: $file");
            }
        }
    }
    
    private function checkBackups() {
        $backupPath = $this->config['backup']['path'];
        $lastBackup = glob($backupPath . '/*')[0] ?? null;
        
        if ($lastBackup && (time() - filemtime($lastBackup)) > 86400) {
            Logger::error('No se han realizado backups en las últimas 24 horas');
        }
    }
    
    private function checkDatabaseConnection() {
        try {
            $db = $this->auth->getDB();
            $db->query('SELECT 1');
        } catch (Exception $e) {
            Logger::error('Error en la conexión a la base de datos: ' . $e->getMessage());
        }
    }
    
    private function checkSSLCertificate() {
        $domain = $this->config['session']['domain'];
        $cert = @openssl_x509_parse(openssl_x509_read(file_get_contents("https://$domain")));
        
        if ($cert && $cert['validTo_time_t'] < (time() + (30 * 86400))) {
            Logger::warning('Certificado SSL expirará en menos de 30 días');
        }
    }
    
    private function checkErrorLogs() {
        $errorLog = $this->config['logs']['error_log'];
        if (file_exists($errorLog)) {
            $errors = file_get_contents($errorLog);
            if (strpos($errors, '[error]') !== false) {
                Logger::warning('Se encontraron errores en el log de PHP');
            }
        }
    }
}

// Ejecutar verificaciones
$checker = new SecurityCheck();
$checker->runChecks(); 