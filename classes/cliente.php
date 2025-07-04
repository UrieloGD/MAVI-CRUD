<?php
require_once 'database.php';

// Clase cliente para manejar operaciones CRUD
class Cliente {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Crear cliente
    public function create($data) {
        try {
            $pdo = $this->db->getPdo();
            
            // Verificar si el email ya existe
            if ($this->emailExists($data['correo_electronico'])) {
                throw new Exception('El correo electrónico ya está registrado');
            }

            $stmt = $pdo->prepare("
                INSERT INTO clientes (nombres, apellido_paterno, apellido_materno, domicilio, correo_electronico) 
                VALUES (?, ?, ?, ?, ?)
            ");
            
            $stmt->execute([
                $data['nombres'],
                $data['apellido_paterno'],
                $data['apellido_materno'],
                $data['domicilio'],
                $data['correo_electronico']
            ]);

            return $pdo->lastInsertId();
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    // Obtener cliente por ID
    public function getById($id) {
        try {
            $pdo = $this->db->getPdo();
            $stmt = $pdo->prepare("SELECT * FROM clientes WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (Exception $e) {
            throw new Exception('Error al obtener cliente: ' . $e->getMessage());
        }
    }

    // Obtener todos los clientes con paginación y búsqueda opcional
    public function getAll($limit = 10, $offset = 0, $search = '') {
        try {
            $pdo = $this->db->getPdo();
            $whereClause = '';
            $params = [];

            if (!empty($search)) {
                $whereClause = "WHERE nombres LIKE ? OR apellido_paterno LIKE ? OR apellido_materno LIKE ? OR correo_electronico LIKE ?";
                $searchTerm = "%{$search}%";
                $params = [$searchTerm, $searchTerm, $searchTerm, $searchTerm];
            }

            $stmt = $pdo->prepare("
                SELECT * FROM clientes 
                {$whereClause} 
                ORDER BY id ASC 
                LIMIT ? OFFSET ?
            ");
            
            $params[] = $limit;
            $params[] = $offset;
            $stmt->execute($params);
            
            return $stmt->fetchAll();
        } catch (Exception $e) {
            throw new Exception('Error al obtener clientes: ' . $e->getMessage());
        }
    }

    // Contar clientes con búsqueda opcional
    public function count($search = '') {
        try {
            $pdo = $this->db->getPdo();
            $whereClause = '';
            $params = [];

            if (!empty($search)) {
                $whereClause = "WHERE nombres LIKE ? OR apellido_paterno LIKE ? OR apellido_materno LIKE ? OR correo_electronico LIKE ?";
                $searchTerm = "%{$search}%";
                $params = [$searchTerm, $searchTerm, $searchTerm, $searchTerm];
            }

            $stmt = $pdo->prepare("SELECT COUNT(*) FROM clientes {$whereClause}");
            $stmt->execute($params);
            return $stmt->fetchColumn();
        } catch (Exception $e) {
            throw new Exception('Error al contar clientes: ' . $e->getMessage());
        }
    }

    // Actualizar cliente
    public function update($id, $data) {
        try {
            $pdo = $this->db->getPdo();
            
            // Verificar si el email ya existe para otro cliente
            if ($this->emailExists($data['correo_electronico'], $id)) {
                throw new Exception('El correo electrónico ya está registrado');
            }

            $stmt = $pdo->prepare("
                UPDATE clientes 
                SET nombres = ?, apellido_paterno = ?, apellido_materno = ?, domicilio = ?, correo_electronico = ?
                WHERE id = ?
            ");
            
            $stmt->execute([
                $data['nombres'],
                $data['apellido_paterno'],
                $data['apellido_materno'],
                $data['domicilio'],
                $data['correo_electronico'],
                $id
            ]);

            return $stmt->rowCount() > 0;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    // Eliminar cliente
    public function delete($id) {
        try {
            $pdo = $this->db->getPdo();
            $stmt = $pdo->prepare("DELETE FROM clientes WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->rowCount() > 0;
        } catch (Exception $e) {
            throw new Exception('Error al eliminar cliente: ' . $e->getMessage());
        }
    }

    // Verificar si el correo electrónico ya existe
    private function emailExists($email, $excludeId = null) {
        try {
            $pdo = $this->db->getPdo();
            $sql = "SELECT COUNT(*) FROM clientes WHERE correo_electronico = ?";
            $params = [$email];

            if ($excludeId) {
                $sql .= " AND id != ?";
                $params[] = $excludeId;
            }

            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchColumn() > 0;
        } catch (Exception $e) {
            return false;
        }
    }

    // Validar datos del cliente
    public function validate($data) {
        $errors = [];

        // Validar nombres
        if (empty($data['nombres'])) {
            $errors[] = 'El campo nombres es requerido';
        } elseif (strlen($data['nombres']) < 2) {
            $errors[] = 'El campo nombres debe tener al menos 2 caracteres';
        }

        // Validar apellido paterno
        if (empty($data['apellido_paterno'])) {
            $errors[] = 'El apellido paterno es requerido';
        } elseif (strlen($data['apellido_paterno']) < 2) {
            $errors[] = 'El apellido paterno debe tener al menos 2 caracteres';
        }

        // Validar apellido materno
        if (empty($data['apellido_materno'])) {
            $errors[] = 'El apellido materno es requerido';
        } elseif (strlen($data['apellido_materno']) < 2) {
            $errors[] = 'El apellido materno debe tener al menos 2 caracteres';
        }

        // Validar domicilio
        if (empty($data['domicilio'])) {
            $errors[] = 'El domicilio es requerido';
        } elseif (strlen($data['domicilio']) < 10) {
            $errors[] = 'El domicilio debe tener al menos 10 caracteres';
        }

        // Validar correo electrónico
        if (empty($data['correo_electronico'])) {
            $errors[] = 'El correo electrónico es requerido';
        } elseif (!filter_var($data['correo_electronico'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'El correo electrónico no es válido';
        }

        return $errors;
    }
}
?>