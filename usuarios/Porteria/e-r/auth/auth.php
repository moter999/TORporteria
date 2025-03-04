<?php
session_start();

class Auth {
    private static $config;
    private static $instance = null;
    private $db;

    private function __construct() {
        self::$config = require __DIR__ . '/../config/security.php';
        $this->initDatabase();
    }

    private function initDatabase() {
        try {
            $this->db = new mysqli(
                self::$config['database']['host'],
                self::$config['database']['user'],
                self::$config['database']['pass'],
                self::$config['database']['name']
            );

            if ($this->db->connect_error) {
                throw new Exception("Error de conexión: " . $this->db->connect_error);
            }

            $this->db->set_charset("utf8mb4");
        } catch (Exception $e) {
            error_log("Error de conexión a la base de datos: " . $e->getMessage());
            die("Error de conexión a la base de datos");
        }
    }

    public function getDB() {
        if (!$this->db || $this->db->connect_error) {
            $this->initDatabase();
        }
        return $this->db;
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function checkAuth() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: login.php');
            exit();
        }

        if ($this->sessionExpired()) {
            $this->logout();
            header('Location: login.php?error=session_expired');
            exit();
        }

        $this->regenerateSession();
    }

    private function sessionExpired() {
        return isset($_SESSION['last_activity']) && 
               (time() - $_SESSION['last_activity'] > self::$config['session']['lifetime']);
    }

    private function regenerateSession() {
        if (!isset($_SESSION['last_regeneration']) || 
            time() - $_SESSION['last_regeneration'] > 300) {
            session_regenerate_id(true);
            $_SESSION['last_regeneration'] = time();
        }
        $_SESSION['last_activity'] = time();
    }

    public function login($username, $password) {
        try {
            if ($this->isUserLocked($username)) {
                throw new Exception('Cuenta bloqueada temporalmente');
            }

            $stmt = $this->db->prepare("SELECT id, password, failed_attempts FROM users WHERE username = ? AND active = 1");
            if (!$stmt) {
                throw new Exception('Error al preparar la consulta');
            }

            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 1) {
                $user = $result->fetch_assoc();
                if (password_verify($password, $user['password'])) {
                    // Resetear intentos fallidos
                    $this->resetFailedAttempts($username);
                    
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['last_activity'] = time();
                    $_SESSION['last_regeneration'] = time();
                    
                    // Actualizar último login
                    $this->updateLastLogin($user['id']);
                    
                    return true;
                }
            }

            // Incrementar intentos fallidos
            $this->incrementFailedAttempts($username);
            return false;

        } catch (Exception $e) {
            error_log("Error en login: " . $e->getMessage());
            return false;
        }
    }

    private function isUserLocked($username) {
        $stmt = $this->db->prepare("SELECT locked_until FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if ($user['locked_until'] !== null) {
                $locked_until = strtotime($user['locked_until']);
                if ($locked_until > time()) {
                    return true;
                }
            }
        }
        return false;
    }

    private function incrementFailedAttempts($username) {
        $stmt = $this->db->prepare("
            UPDATE users 
            SET failed_attempts = failed_attempts + 1,
                locked_until = CASE 
                    WHEN failed_attempts + 1 >= ? THEN DATE_ADD(NOW(), INTERVAL ? SECOND)
                    ELSE NULL 
                END
            WHERE username = ?
        ");
        
        $max_attempts = self::$config['security']['max_login_attempts'];
        $lockout_time = self::$config['security']['lockout_time'];
        
        $stmt->bind_param("iis", $max_attempts, $lockout_time, $username);
        $stmt->execute();
    }

    private function resetFailedAttempts($username) {
        $stmt = $this->db->prepare("
            UPDATE users 
            SET failed_attempts = 0,
                locked_until = NULL
            WHERE username = ?
        ");
        $stmt->bind_param("s", $username);
        $stmt->execute();
    }

    private function updateLastLogin($user_id) {
        $stmt = $this->db->prepare("
            UPDATE users 
            SET last_login = NOW()
            WHERE id = ?
        ");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
    }

    public function logout() {
        $_SESSION = array();
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600, '/');
        }
        session_destroy();
    }

    public function generateCSRFToken() {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    public function validateCSRFToken($token) {
        return isset($_SESSION['csrf_token']) && 
               hash_equals($_SESSION['csrf_token'], $token);
    }

    public function sanitizeInput($data) {
        if (is_array($data)) {
            return array_map([$this, 'sanitizeInput'], $data);
        }
        $data = trim($data);
        $data = stripslashes($data);
        return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    }
} 