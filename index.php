<?php
require_once __DIR__ . '../classes/Auth.php';
require_once __DIR__ . '../classes/Cliente.php';

$auth = new Auth();
$auth->requireAuth();

$cliente = new Cliente();
$totalClientes = $cliente->count();
$clientesActivos = $cliente->countActive();
$clientesInactivos = $cliente->countInactive();
$currentUser = $auth->getCurrentUser();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Gestión de Clientes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.0/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="./assets/css/custom.css">
</head>
<body>
    <!-- Mobile menu toggle -->
    <button class="mobile-menu-toggle" onclick="toggleSidebar()">
        <i class="bi bi-list"></i>
    </button>
    
    <!-- Sidebar overlay for mobile -->
    <div class="sidebar-overlay" onclick="toggleSidebar()"></div>
    
    <!-- Loading spinner -->
    <div class="loading-spinner">
        <div class="spinner-border" role="status">
            <span class="visually-hidden">Cargando...</span>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar p-3" id="sidebar">
                <div class="text-center mb-4">
                    <i class="bi bi-people-fill fs-1"></i>
                    <h4 class="mt-2">Clientes</h4>
                </div>
                
                <nav class="nav flex-column">
                    <a class="nav-link active" href="#" data-section="dashboard">
                        <i class="bi bi-house-door me-2"></i>Dashboard
                    </a>
                    <a class="nav-link" href="#" data-section="clientes">
                        <i class="bi bi-people me-2"></i>Gestión de Clientes
                    </a>
                </nav>

                <div class="mt-auto">
                    <hr class="border-light">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-person-circle me-2"></i>
                        <div>
                            <small class="text-truncate d-block" title="<?php echo htmlspecialchars($currentUser); ?>">
                                <?php echo htmlspecialchars($currentUser); ?>
                            </small>
                        </div>
                    </div>
                    <a class="dropdown-item" href="./functions/change_password.php">
                        <i class="bi bi-key"></i> Cambiar Contraseña
                    </a>
                    <button class="btn btn-outline-light btn-sm mt-2 w-100" onclick="logout()">
                        <i class="bi bi-box-arrow-right me-2"></i>Cerrar Sesión
                    </button>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 main-content p-4">

                <!-- Dashboard Section -->
                <div id="dashboard-section">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2><i class="bi bi-house-door me-2"></i>Dashboard</h2>
                        <span class="badge bg-primary">Bienvenido, <?php echo htmlspecialchars($currentUser); ?></span>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card stats-card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1">
                                            <h5 class="card-title">Total Clientes</h5>
                                            <h2 class="mb-0" id="totalClientesCount"><?php echo $totalClientes; ?></h2>
                                        </div>
                                        <div class="fs-1 text-white">
                                            <i class="bi bi-people-fill"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card stats-card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1">
                                            <h5 class="card-title">Clientes Activos</h5>
                                            <h2 class="mb-0" id="activosCount"><?php echo $clientesActivos; ?></h2>
                                        </div>
                                        <div class="fs-1 text-white">
                                            <i class="bi bi-check-circle-fill"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card stats-card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1">
                                            <h5 class="card-title">Clientes Inactivos</h5>
                                            <h2 class="mb-0" id="inactivosCount"><?php echo $clientesInactivos; ?></h2>
                                        </div>
                                        <div class="fs-1 text-white">
                                            <i class="bi bi-pause-circle-fill"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Acciones Rápidas</h5>
                                </div>
                                <div class="card-body">
                                    <div class="quick-actions">
                                        <a href="#" class="quick-action-btn" onclick="showSection('clientes')">
                                            <i class="bi bi-people"></i>
                                            <span>Gestionar Clientes</span>
                                        </a>
                                        <a href="#" class="quick-action-btn" onclick="showClienteModal()">
                                            <i class="bi bi-person-plus"></i>
                                            <span>Nuevo Cliente</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Clientes Section -->
                <div id="clientes-section" style="display: none;">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2><i class="bi bi-people me-2"></i>Gestión de Clientes</h2>
                        <button class="btn btn-primary" onclick="showClienteModal()">
                            <i class="bi bi-person-plus me-2"></i>Nuevo Cliente
                        </button>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col-md-3">
                                    <h5 class="card-title mb-0">
                                        Lista de Clientes
                                        <span class="badge bg-secondary ms-2" id="clientesCount">0</span>
                                    </h5>
                                </div>
                                <div class="col-md-2">
                                    <div class="d-flex align-items-center">
                                        <label for="recordsPerPage" class="form-label me-2 mb-0">Mostrar:</label>
                                        <select class="form-select form-select-sm" id="recordsPerPage" style="width: auto;">
                                            <option value="10" selected>10</option>
                                            <option value="20">20</option>
                                            <option value="50">50</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="d-flex align-items-center">
                                        <label for="statusFilter" class="form-label me-2 mb-0">Filtrar:</label>
                                        <select class="form-select form-select-sm" id="statusFilter">
                                            <option value="">Todos</option>
                                            <option value="activo">Activos</option>
                                            <option value="inactivo">Inactivos</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="search-container">
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="searchInput" placeholder="Buscar clientes...">
                                            <button class="search-clear" onclick="clearSearch()">
                                                <i class="bi bi-x-circle-fill"></i>
                                            </button>
                                            <span class="input-group-text">
                                                <i class="bi bi-search"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="table-container">
                                <div class="table-responsive">
                                    <table id="clientesTable" class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Nombres</th>
                                                <th>Apellidos</th>
                                                <th>Correo</th>
                                                <th>Estatus</th>
                                                <th class="text-center">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Datos cargados via Ajax -->
                                        </tbody>
                                    </table>
                                </div>
                                
                                <div class="empty-state d-none">
                                    <i class="bi bi-people"></i>
                                    <h4>No hay clientes registrados</h4>
                                    <p>Comienza agregando tu primer cliente</p>
                                    <button class="btn btn-primary" onclick="showClienteModal()">
                                        <i class="bi bi-person-plus me-2"></i>Agregar Cliente
                                    </button>
                                </div>
                            </div>
                            
                            <div id="paginationContainer" class="mt-3"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Cliente -->
    <div class="modal fade" id="clienteModal" tabindex="-1" aria-labelledby="clienteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="clienteModalLabel">
                        <i class="bi bi-person-plus me-2"></i>Nuevo Cliente
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <form id="clienteForm" novalidate>
                    <div class="modal-body">
                        <input type="hidden" id="clienteId" name="id">
                        
                        <div class="row">
                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="nombres" name="nombres" placeholder="Nombres" required>
                                    <label for="nombres">Nombres *</label>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="apellido_paterno" name="apellido_paterno" placeholder="Apellido Paterno" required>
                                    <label for="apellido_paterno">Apellido Paterno *</label>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="apellido_materno" name="apellido_materno" placeholder="Apellido Materno" required>
                                    <label for="apellido_materno">Apellido Materno *</label>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="email" class="form-control" id="correo_electronico" name="correo_electronico" placeholder="Correo Electrónico" required>
                                    <label for="correo_electronico">Correo Electrónico *</label>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-floating">
                                    <textarea class="form-control" id="domicilio" name="domicilio" placeholder="Domicilio" style="height: 100px" required></textarea>
                                    <label for="domicilio">Domicilio *</label>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-floating">
                                    <select class="form-select" id="estatus" name="estatus" required>
                                        <option value="activo" selected>Activo</option>
                                        <option value="inactivo">Inactivo</option>
                                    </select>
                                    <label for="estatus">Estatus *</label>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <small class="text-muted">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Los campos marcados con * son obligatorios
                                </small>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x me-2"></i>Cancelar
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i>Guardar Cliente
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.0/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.0/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="./assets/js/index.js"></script>
    <script src="./assets/js/main.js"></script>
</body>
</html>