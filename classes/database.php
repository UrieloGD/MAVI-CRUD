<?php
// Path: classes/database.php
// Clase para manejar la conexión a la base de datos
require_once __DIR__ . '/../config/database.php';

class Database {
    private $host = DB_HOST;
    private $dbname = DB_NAME;
    private $username = DB_USER;
    private $password = DB_PASS;
    private $charset = DB_CHARSET;
    private $pdo;

    // Establecer conexión a la base de datos 
    public function connect() {
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset={$this->charset}";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            
            $this->pdo = new PDO($dsn, $this->username, $this->password, $options);
            return $this->pdo;
        } catch (PDOException $e) {
            throw new Exception('Error de conexión: ' . $e->getMessage());
        }
    }

    // Obtener instancia de PDO
    public function getPdo() {
        if (!$this->pdo) {
            $this->connect();
        }
        return $this->pdo;
    }

    // Cerrar conexión
    public function disconnect() {
        $this->pdo = null;
    }
}
?>