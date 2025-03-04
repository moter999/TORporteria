<?php
class Logger {
    private static $config;
    private static $logLevels = ['debug' => 0, 'info' => 1, 'warning' => 2, 'error' => 3];
    
    public static function init() {
        self::$config = require __DIR__ . '/../config/security.php';
        
        if (!file_exists(self::$config['logs']['path'])) {
            mkdir(self::$config['logs']['path'], 0755, true);
        }
    }
    
    public static function log($level, $message, $context = []) {
        if (!self::$config) {
            self::init();
        }
        
        if (!self::shouldLog($level)) {
            return;
        }
        
        $logFile = self::$config['logs']['path'] . '/app-' . date('Y-m-d') . '.log';
        $timestamp = date('Y-m-d H:i:s');
        $contextStr = !empty($context) ? json_encode($context) : '';
        
        $logMessage = sprintf(
            "[%s] %s: %s %s\n",
            $timestamp,
            strtoupper($level),
            $message,
            $contextStr
        );
        
        file_put_contents($logFile, $logMessage, FILE_APPEND);
        self::rotateLogFiles();
    }
    
    private static function shouldLog($level) {
        if (!isset(self::$config['logs']['enabled']) || !self::$config['logs']['enabled']) {
            return false;
        }
        
        $configLevel = self::$config['logs']['level'];
        return self::$logLevels[$level] >= self::$logLevels[$configLevel];
    }
    
    private static function rotateLogFiles() {
        $logPath = self::$config['logs']['path'];
        $maxFiles = self::$config['logs']['max_files'];
        
        $files = glob($logPath . '/app-*.log');
        if (count($files) > $maxFiles) {
            usort($files, function($a, $b) {
                return filemtime($a) - filemtime($b);
            });
            
            $filesToDelete = array_slice($files, 0, count($files) - $maxFiles);
            foreach ($filesToDelete as $file) {
                unlink($file);
            }
        }
    }
    
    public static function debug($message, $context = []) {
        self::log('debug', $message, $context);
    }
    
    public static function info($message, $context = []) {
        self::log('info', $message, $context);
    }
    
    public static function warning($message, $context = []) {
        self::log('warning', $message, $context);
    }
    
    public static function error($message, $context = []) {
        self::log('error', $message, $context);
    }
} 