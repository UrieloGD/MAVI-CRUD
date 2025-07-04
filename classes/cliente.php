<?php
require_once 'database.php';

class Cliente {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Crear cliente
    public function create($data) {
        try {
            $pdo = $this->db->getPdo();
            
            if ($this->emailExists($data['correo_electronico'])) {
                throw new Exception('El correo electrónico ya está registrado');
            }

            $stmt = $pdo->prepare("
                INSERT INTO clientes (nombres, apellido_paterno, apellido_materno, domicilio, correo_electronico, estatus) 
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            
            $stmt->execute([
                $data['nombres'],
                $data['apellido_paterno'],
                $data['apellido_materno'],
                $data['domicilio'],
                $data['correo_electronico'],
                $data['estatus'] ?? 'activo'
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

    // Obtener todos los clientes con paginación, búsqueda y filtro por estatus
    public function getAll($limit = 10, $offset = 0, $search = '', $status = '') {
        try {
            $pdo = $this->db->getPdo();
            $whereConditions = [];
            $params = [];

            // Filtro de búsqueda
            if (!empty($search)) {
                $whereConditions[] = "(nombres LIKE ? OR apellido_paterno LIKE ? OR apellido_materno LIKE ? OR correo_electronico LIKE ?)";
                $searchTerm = "%{$search}%";
                $params = array_merge($params, [$searchTerm, $searchTerm, $searchTerm, $searchTerm]);
            }

            // Filtro por estatus
            if (!empty($status)) {
                $whereConditions[] = "estatus = ?";
                $params[] = $status;
            }

            $whereClause = '';
            if (!empty($whereConditions)) {
                $whereClause = "WHERE " . implode(' AND ', $whereConditions);
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

    // Contar clientes con búsqueda y filtro por estatus
    public function count($search = '', $status = '') {
        try {
            $pdo = $this->db->getPdo();
            $whereConditions = [];
            $params = [];

            // Filtro de búsqueda
            if (!empty($search)) {
                $whereConditions[] = "(nombres LIKE ? OR apellido_paterno LIKE ? OR apellido_materno LIKE ? OR correo_electronico LIKE ?)";
                $searchTerm = "%{$search}%";
                $params = array_merge($params, [$searchTerm, $searchTerm, $searchTerm, $searchTerm]);
            }

            // Filtro por estatus
            if (!empty($status)) {
                $whereConditions[] = "estatus = ?";
                $params[] = $status;
            }

            $whereClause = '';
            if (!empty($whereConditions)) {
                $whereClause = "WHERE " . implode(' AND ', $whereConditions);
            }

            $stmt = $pdo->prepare("SELECT COUNT(*) FROM clientes {$whereClause}");
            $stmt->execute($params);
            return $stmt->fetchColumn();
        } catch (Exception $e) {
            throw new Exception('Error al contar clientes: ' . $e->getMessage());
        }
    }

    // Contar clientes activos
    public function countActive() {
        try {
            $pdo = $this->db->getPdo();
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM clientes WHERE estatus = 'activo'");
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (Exception $e) {
            throw new Exception('Error al contar clientes activos: ' . $e->getMessage());
        }
    }

    // Contar clientes inactivos
    public function countInactive() {
        try {
            $pdo = $this->db->getPdo();
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM clientes WHERE estatus = 'inactivo'");
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (Exception $e) {
            throw new Exception('Error al contar clientes inactivos: ' . $e->getMessage());
        }
    }

    // Actualizar cliente
    public function update($id, $data) {
        try {
            $pdo = $this->db->getPdo();
            
            if ($this->emailExists($data['correo_electronico'], $id)) {
                throw new Exception('El correo electrónico ya está registrado');
            }

            $stmt = $pdo->prepare("
                UPDATE clientes 
                SET nombres = ?, apellido_paterno = ?, apellido_materno = ?, domicilio = ?, correo_electronico = ?, estatus = ?
                WHERE id = ?
            ");
            
            $stmt->execute([
                $data['nombres'],
                $data['apellido_paterno'],
                $data['apellido_materno'],
                $data['domicilio'],
                $data['correo_electronico'],
                $data['estatus'] ?? 'activo',
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

    // Cambiar estatus del cliente
    public function changeStatus($id, $status) {
        try {
            $pdo = $this->db->getPdo();
            $stmt = $pdo->prepare("UPDATE clientes SET estatus = ? WHERE id = ?");
            $stmt->execute([$status, $id]);
            return $stmt->rowCount() > 0;
        } catch (Exception $e) {
            throw new Exception('Error al cambiar estatus: ' . $e->getMessage());
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

        if (empty($data['nombres'])) {
            $errors[] = 'El campo nombres es requerido';
        } elseif (strlen($data['nombres']) < 2) {
            $errors[] = 'El campo nombres debe tener al menos 2 caracteres';
        }

        if (empty($data['apellido_paterno'])) {
            $errors[] = 'El apellido paterno es requerido';
        } elseif (strlen($data['apellido_paterno']) < 2) {
            $errors[] = 'El apellido paterno debe tener al menos 2 caracteres';
        }

        if (empty($data['apellido_materno'])) {
            $errors[] = 'El apellido materno es requerido';
        } elseif (strlen($data['apellido_materno']) < 2) {
            $errors[] = 'El apellido materno debe tener al menos 2 caracteres';
        }

        if (empty($data['domicilio'])) {
            $errors[] = 'El domicilio es requerido';
        } elseif (strlen($data['domicilio']) < 10) {
            $errors[] = 'El domicilio debe tener al menos 10 caracteres';
        }

        if (empty($data['correo_electronico'])) {
            $errors[] = 'El correo electrónico es requerido';
        } elseif (!filter_var($data['correo_electronico'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'El correo electrónico no es válido';
        }

        if (isset($data['estatus']) && !in_array($data['estatus'], ['activo', 'inactivo'])) {
            $errors[] = 'El estatus debe ser activo o inactivo';
        }

        return $errors;
    }
}
?>