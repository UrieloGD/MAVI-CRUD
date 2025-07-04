<?php
require_once __DIR__ . '/database.php';
require_once __DIR__ . '/../config/database.php';

// Only define constant if not already defined
if (!defined('SESSION_TIMEOUT')) {
    define('SESSION_TIMEOUT', 3600); // 1 hora
}

class Auth {
    private $db;
    
    public function __construct() {
        $this->db = new Database();
    }

    // Helper method to safely start session
    private function startSession() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // Iniciar sesión SIN hash
    public function login($username, $password) {
        try {
            $pdo = $this->db->getPdo();
            $stmt = $pdo->prepare("SELECT id, username, password FROM usuarios WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch();

            // Comparación directa sin hash
            if ($user && $user['password'] === $password) {
                $this->startSession();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['login_time'] = time();
                return true;
            }
            return false;
        } catch (Exception $e) {
            // Para debug temporal
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public function isAuthenticated() {
        $this->startSession();
        
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['login_time'])) {
            return false;
        }

        if (time() - $_SESSION['login_time'] > SESSION_TIMEOUT) {
            $this->logout();
            return false;
        }

        $_SESSION['login_time'] = time();
        return true;
    }

    public function logout() {
        $this->startSession();
        session_destroy();
        return true;
    }

    public function getCurrentUser() {
        $this->startSession();
        return isset($_SESSION['username']) ? $_SESSION['username'] : null;
    }

    public function requireAuth() {
        if (!$this->isAuthenticated()) {
            header('Location: login.php');
            exit;
        }
    }
}
?>