<?php
require_once './../classes/auth.php';
require_once './../classes/cliente.php';

header('Content-Type: application/json');

$auth = new Auth();
if (!$auth->isAuthenticated()) {
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit;
}

$cliente = new Cliente();
$action = $_POST['action'] ?? $_GET['action'] ?? '';

try {
    switch ($action) {
        case 'create':
            $data = [
                'nombres' => $_POST['nombres'] ?? '',
                'apellido_paterno' => $_POST['apellido_paterno'] ?? '',
                'apellido_materno' => $_POST['apellido_materno'] ?? '',
                'domicilio' => $_POST['domicilio'] ?? '',
                'correo_electronico' => $_POST['correo_electronico'] ?? ''
           ];

           // Validar datos
           $errors = $cliente->validate($data);
           if (!empty($errors)) {
               echo json_encode([
                   'success' => false,
                   'message' => 'Datos inválidos',
                   'errors' => $errors
               ]);
               exit;
           }

           $id = $cliente->create($data);
           echo json_encode([
               'success' => true,
               'message' => 'Cliente creado exitosamente',
               'id' => $id
           ]);
           break;

        case 'read':
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
            $search = isset($_GET['search']) ? trim($_GET['search']) : '';
            
            $offset = ($page - 1) * $limit;
            
            try {
                $clientes = $cliente->getAll($limit, $offset, $search);
                $totalClientes = $cliente->count($search);
                $totalPages = ceil($totalClientes / $limit);
                
                $startRecord = $totalClientes > 0 ? $offset + 1 : 0;
                $endRecord = $offset + count($clientes);
                
                $response = [
                    'success' => true,
                    'data' => $clientes,
                    'pagination' => [
                        'currentPage' => $page,
                        'totalPages' => $totalPages,
                        'total' => $totalClientes,
                        'limit' => $limit,
                        'startRecord' => $startRecord,
                        'endRecord' => $endRecord
                    ]
                ];
                
                echo json_encode($response);
            } catch (Exception $e) {
                echo json_encode([
                    'success' => false,
                    'message' => $e->getMessage()
                ]);
            }
            break;

       case 'get':
           $id = intval($_GET['id'] ?? 0);
           if ($id <= 0) {
               throw new Exception('ID inválido');
           }

           $data = $cliente->getById($id);
           if (!$data) {
               throw new Exception('Cliente no encontrado');
           }

           echo json_encode([
               'success' => true,
               'data' => $data
           ]);
           break;

       case 'update':
           $id = intval($_POST['id'] ?? 0);
           if ($id <= 0) {
               throw new Exception('ID inválido');
           }

           $data = [
               'nombres' => $_POST['nombres'] ?? '',
               'apellido_paterno' => $_POST['apellido_paterno'] ?? '',
               'apellido_materno' => $_POST['apellido_materno'] ?? '',
               'domicilio' => $_POST['domicilio'] ?? '',
               'correo_electronico' => $_POST['correo_electronico'] ?? ''
           ];

           // Validar datos
           $errors = $cliente->validate($data);
           if (!empty($errors)) {
               echo json_encode([
                   'success' => false,
                   'message' => 'Datos inválidos',
                   'errors' => $errors
               ]);
               exit;
           }

           $updated = $cliente->update($id, $data);
           if (!$updated) {
               throw new Exception('No se pudo actualizar el cliente');
           }

           echo json_encode([
               'success' => true,
               'message' => 'Cliente actualizado exitosamente'
           ]);
           break;

       case 'delete':
           $id = intval($_POST['id'] ?? 0);
           if ($id <= 0) {
               throw new Exception('ID inválido');
           }

           $deleted = $cliente->delete($id);
           if (!$deleted) {
               throw new Exception('No se pudo eliminar el cliente');
           }

           echo json_encode([
               'success' => true,
               'message' => 'Cliente eliminado exitosamente'
           ]);
           break;

       case 'logout':
           $auth->logout();
           echo json_encode([
               'success' => true,
               'message' => 'Sesión cerrada exitosamente'
           ]);
           break;

       default:
           throw new Exception('Acción no válida');
   }

} catch (Exception $e) {
   echo json_encode([
       'success' => false,
       'message' => $e->getMessage()
   ]);
}
?>