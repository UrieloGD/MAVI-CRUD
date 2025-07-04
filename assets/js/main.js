//Sistema de Gestión de Clientes - JavaScript Principal
// assets/js/main.js
// Variables globales
let clientesTable;
let currentPage = 1;
let currentSearch = '';
let currentLimit = 10; // Valor por defecto

// Inicializar cuando el DOM esté listo
$(document).ready(function() {
    initializeApp();
});

// Inicializar la aplicación
function initializeApp() {
    // Configurar eventos de navegación
    setupNavigation();
    
    // Configurar eventos de formularios
    setupForms();
    
    // Configurar búsqueda
    setupSearch();
    
    // Configurar paginación
    setupPagination();
    
    // Configurar scroll de tabla
    setupTableScroll();
    
    // Ocultar spinner inicial
    hideSpinner();
    
    // Cargar estadísticas del dashboard
    updateDashboardStats();
    
    // Cargar datos iniciales solo si estamos en la sección de clientes
    if (window.location.hash === '#clientes' || $('.nav-link[data-section="clientes"]').hasClass('active')) {
        loadClientes();
    }
}

// Configurar navegación
function setupNavigation() {
    $('.nav-link').on('click', function(e) {
        e.preventDefault();
        const section = $(this).data('section');
        showSection(section);
        
        // Actualizar navegación activa
        $('.nav-link').removeClass('active');
        $(this).addClass('active');
    });
}

// Mostrar sección específica
function showSection(section) {
    // Ocultar todas las secciones
    $('[id$="-section"]').hide();
    
    // Mostrar sección específica
    $(`#${section}-section`).show();
    
    // Cargar datos según la sección
    if (section === 'clientes') {
        loadClientes();
    }
}

// Configurar formularios
function setupForms() {
    // Formulario de cliente
    $('#clienteForm').on('submit', function(e) {
        e.preventDefault();
        saveCliente();
    });
    
    // Validación en tiempo real
    $('#clienteForm input, #clienteForm textarea').on('input', function() {
        validateField($(this));
    });
}

// Configurar búsqueda y filtros
function setupSearch() {
    let searchTimeout;
    
    // Búsqueda
    $('#searchInput').on('input', function() {
        clearTimeout(searchTimeout);
        const searchTerm = $(this).val();
        
        searchTimeout = setTimeout(function() {
            currentSearch = searchTerm;
            currentPage = 1;
            loadClientes();
        }, 500);
    });
    
    // Filtro por estatus
    $('#statusFilter').on('change', function() {
        currentPage = 1;
        loadClientes();
    });
    
    // Selector de registros por página
    $('#recordsPerPage').on('change', function() {
        currentLimit = parseInt($(this).val());
        currentPage = 1; // Reiniciar a la primera página
        loadClientes();
    });
}

function setupPagination() {
    // Selector de registros por página
    $('#recordsPerPage').on('change', function() {
        currentLimit = parseInt($(this).val());
        currentPage = 1; // Reiniciar a la primera página
        loadClientes();
    });
}

// Cargar clientes desde el servidor
function loadClientes() {
    showSpinner();
    
    const statusFilter = $('#statusFilter').val();
    
    $.ajax({
        url: 'ajax/clientes.php',
        method: 'GET',
        data: {
            action: 'read',
            page: currentPage,
            limit: currentLimit,
            search: currentSearch,
            status: statusFilter
        },
        dataType: 'json',
        success: function(response) {
            hideSpinner();
            
            if (response.success) {
                renderClientesTable(response.data);
                renderPagination(response.pagination);
                updateClientesCount(response.pagination.total);
            } else {
                showAlert('error', 'Error al cargar clientes', response.message);
            }
        },
        error: function(xhr, status, error) {
            hideSpinner();
            console.error('Error al cargar clientes:', error);
            showAlert('error', 'Error', 'Error de conexión al servidor');
        }
    });
}

// Función para configurar el scroll de la tabla
function setupTableScroll() {
    const tableContainer = $('.table-responsive');
    const scrollIndicator = $('.scroll-indicator');
    
    // Detectar si hay scroll disponible
    tableContainer.on('scroll', function() {
        const scrollTop = $(this).scrollTop();
        const scrollHeight = $(this)[0].scrollHeight;
        const clientHeight = $(this)[0].clientHeight;
        
        // Mostrar/ocultar indicador de scroll
        if (scrollHeight > clientHeight) {
            if (scrollTop > 10) {
                scrollIndicator.addClass('show');
            } else {
                scrollIndicator.removeClass('show');
            }
        }
    });
}


// Función para ajustar altura de tabla según registros
function adjustTableHeight(recordCount) {
    const tableContainer = $('.table-responsive');
    const minHeight = 300; // Altura mínima
    const maxHeight = 600; // Altura máxima
    
    if (recordCount >= 20) {
        // Para 20 o más registros, usar altura máxima con scroll
        tableContainer.css({
            'max-height': maxHeight + 'px',
            'overflow-y': 'auto'
        });
    } else if (recordCount >= 10) {
        // Para 10-19 registros, altura intermedia
        tableContainer.css({
            'max-height': '600px',
            'overflow-y': 'auto'
        });
    } else {
        // Para menos de 10 registros, altura automática
        tableContainer.css({
            'max-height': 'none',
            'overflow-y': 'visible'
        });
    }
}
// Configurar búsqueda y filtros
function setupSearch() {
    let searchTimeout;
    
    // Búsqueda
    $('#searchInput').on('input', function() {
        clearTimeout(searchTimeout);
        const searchTerm = $(this).val();
        
        searchTimeout = setTimeout(function() {
            currentSearch = searchTerm;
            currentPage = 1;
            loadClientes();
        }, 500);
    });
    
    // Filtro por estatus
    $('#statusFilter').on('change', function() {
        currentPage = 1;
        loadClientes();
    });
}

// Limpiar búsqueda
function clearSearch() {
    $('#searchInput').val('');
    currentSearch = '';
    currentPage = 1;
    loadClientes();
}

// Cargar clientes desde el servidor
function loadClientes() {
    showSpinner();
    
    const statusFilter = $('#statusFilter').val();
    
    $.ajax({
        url: 'ajax/clientes.php',
        method: 'GET',
        data: {
            action: 'read',
            page: currentPage,
            limit: currentLimit,
            search: currentSearch,
            status: statusFilter
        },
        dataType: 'json',
        success: function(response) {
            hideSpinner();
            
            if (response.success) {
                renderClientesTable(response.data);
                renderPagination(response.pagination);
                updateClientesCount(response.pagination.total);
                // Actualizar estadísticas del dashboard
                updateDashboardStats();
            } else {
                showAlert('error', 'Error al cargar clientes', response.message);
            }
        },
        error: function(xhr, status, error) {
            hideSpinner();
            console.error('Error al cargar clientes:', error);
            showAlert('error', 'Error', 'Error de conexión al servidor');
        }
    });
}

// Renderizar tabla de clientes
function renderClientesTable(clientes) {
    const tbody = $('#clientesTable tbody');
    tbody.empty();
    
    if (clientes.length === 0) {
        tbody.append(`
            <tr>
                <td colspan="6" class="text-center">
                    <div class="py-4">
                        <i class="bi bi-inbox fs-1 text-muted"></i>
                        <p class="text-muted mt-2">No se encontraron clientes</p>
                    </div>
                </td>
            </tr>
        `);
        // Quitar scroll para tabla vacía
        $('.table-responsive').css({
            'max-height': 'none',
            'overflow-y': 'visible'
        });
        return;
    }
    
    clientes.forEach(cliente => {
        const statusBadge = cliente.estatus === 'activo' 
            ? '<span class="badge bg-success">Activo</span>' 
            : '<span class="badge bg-secondary">Inactivo</span>';
            
        const statusIcon = cliente.estatus === 'activo' 
            ? '<i class="bi bi-toggle-on text-success"></i>' 
            : '<i class="bi bi-toggle-off text-muted"></i>';
            
        const statusAction = cliente.estatus === 'activo' 
            ? `<button class="btn btn-sm btn-outline-warning" onclick="changeClienteStatus(${cliente.id}, 'inactivo')" title="Desactivar">
                <i class="bi bi-toggle-off"></i>
               </button>`
            : `<button class="btn btn-sm btn-outline-success" onclick="changeClienteStatus(${cliente.id}, 'activo')" title="Activar">
                <i class="bi bi-toggle-on"></i>
               </button>`;
        
        const row = `
            <tr class="${cliente.estatus === 'inactivo' ? 'table-secondary' : ''}">
                <td><strong>${cliente.id}</strong></td>
                <td>${escapeHtml(cliente.nombres)}</td>
                <td>${escapeHtml(cliente.apellido_paterno + ' ' + cliente.apellido_materno)}</td>
                <td>${escapeHtml(cliente.correo_electronico)}</td>
                <td>${statusBadge}</td>
                <td class="text-center"> <!-- Agregar text-center aquí -->
                    <div class="btn-group" role="group">
                        <button class="btn btn-sm btn-outline-primary" onclick="editCliente(${cliente.id})" title="Editar">
                            <i class="bi bi-pencil"></i>
                        </button>
                        ${statusAction}
                        <button class="btn btn-sm btn-outline-danger" onclick="deleteCliente(${cliente.id})" title="Eliminar">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
        tbody.append(row);
    });
    
    // Ajustar altura de tabla según cantidad de registros
    adjustTableHeight(clientes.length);
    
    // Configurar scroll después de renderizar
    setTimeout(setupTableScroll, 100);
}

// Cambiar estatus de cliente
function changeClienteStatus(id, newStatus) {
    const statusText = newStatus === 'activo' ? 'activar' : 'desactivar';
    const statusIcon = newStatus === 'activo' ? 'success' : 'warning';
    
    Swal.fire({
        title: `¿${statusText.charAt(0).toUpperCase() + statusText.slice(1)} cliente?`,
        text: `Se cambiará el estatus del cliente a ${newStatus}`,
        icon: statusIcon,
        showCancelButton: true,
        confirmButtonColor: newStatus === 'activo' ? '#28a745' : '#ffc107',
        cancelButtonColor: '#6c757d',
        confirmButtonText: `Sí, ${statusText}`,
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: 'ajax/clientes.php',
                method: 'POST',
                data: {
                    action: 'changeStatus',
                    id: id,
                    status: newStatus
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        showAlert('success', '¡Éxito!', response.message);
                        loadClientes();
                        // Actualizar contadores del dashboard
                        updateDashboardStats();
                    } else {
                        showAlert('error', 'Error', response.message);
                    }
                },
                error: function() {
                    showAlert('error', 'Error', 'Error de conexión al servidor');
                }
            });
        }
    });
}

// Actualizar estadísticas del dashboard
function updateDashboardStats() {
    $.ajax({
        url: 'ajax/clientes.php',
        method: 'GET',
        data: {
            action: 'getStats'
        },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                $('#totalClientesCount').text(response.data.total);
                $('#activosCount').text(response.data.activos);
                $('#inactivosCount').text(response.data.inactivos);
            }
        }
    });
}

// Renderizar paginación
function renderPagination(pagination) {
    const paginationContainer = $('#paginationContainer');
    paginationContainer.empty();
    
    if (pagination.totalPages <= 1) {
        return;
    }
    
    let paginationHtml = '<nav aria-label="Navegación de páginas"><ul class="pagination justify-content-center">';
    
    // Botón anterior
    if (pagination.currentPage > 1) {
        paginationHtml += `
            <li class="page-item">
                <button class="page-link" onclick="changePage(${pagination.currentPage - 1})">
                    <i class="bi bi-chevron-left"></i>
                </button>
            </li>
        `;
    }
    
    // Páginas
    const startPage = Math.max(1, pagination.currentPage - 2);
    const endPage = Math.min(pagination.totalPages, pagination.currentPage + 2);
    
    if (startPage > 1) {
        paginationHtml += `
            <li class="page-item">
                <button class="page-link" onclick="changePage(1)">1</button>
            </li>
        `;
        if (startPage > 2) {
            paginationHtml += '<li class="page-item disabled"><span class="page-link">...</span></li>';
        }
    }
    
    for (let i = startPage; i <= endPage; i++) {
        const activeClass = i === pagination.currentPage ? 'active' : '';
        paginationHtml += `
            <li class="page-item ${activeClass}">
                <button class="page-link" onclick="changePage(${i})">${i}</button>
            </li>
        `;
    }
    
    if (endPage < pagination.totalPages) {
        if (endPage < pagination.totalPages - 1) {
            paginationHtml += '<li class="page-item disabled"><span class="page-link">...</span></li>';
        }
        paginationHtml += `
            <li class="page-item">
                <button class="page-link" onclick="changePage(${pagination.totalPages})">${pagination.totalPages}</button>
            </li>
        `;
    }
    
    // Botón siguiente
    if (pagination.currentPage < pagination.totalPages) {
        paginationHtml += `
            <li class="page-item">
                <button class="page-link" onclick="changePage(${pagination.currentPage + 1})">
                    <i class="bi bi-chevron-right"></i>
                </button>
            </li>
        `;
    }
    
    paginationHtml += '</ul></nav>';
    
    // Información de paginación
    const infoHtml = `
        <div class="pagination-info text-muted small">
            Mostrando ${pagination.startRecord} - ${pagination.endRecord} de ${pagination.total} registros
        </div>
    `;
    
    paginationContainer.html(paginationHtml + infoHtml);
}

// Cambiar página
function changePage(page) {
    currentPage = page;
    loadClientes();
}

// Actualizar contador de clientes
function updateClientesCount(count) {
    $('#clientesCount').text(count);
}

// Mostrar modal para crear o editar cliente
function showClienteModal(id = null) {
    const modalElement = document.getElementById('clienteModal');
    const modal = bootstrap.Modal.getOrCreateInstance(modalElement);
    const form = document.getElementById('clienteForm');
    
    // Limpiar formulario
    form.reset();
    clearValidation();
    
    if (id) {
        // Modo edición
        document.getElementById('clienteModalLabel').innerHTML = '<i class="bi bi-pencil me-2"></i>Editar Cliente';
        document.getElementById('clienteId').value = id;
        loadClienteData(id);
    } else {
        // Modo creación
        document.getElementById('clienteModalLabel').innerHTML = '<i class="bi bi-person-plus me-2"></i>Nuevo Cliente';
        document.getElementById('clienteId').value = '';
    }
    
    modal.show();
}

// Cargar datos de cliente para edición
function loadClienteData(id) {
    showSpinner();
    
    $.ajax({
        url: 'ajax/clientes.php',
        method: 'GET',
        data: {
            action: 'get',
            id: id
        },
        dataType: 'json',
        success: function(response) {
            hideSpinner();
            
            if (response.success) {
                const cliente = response.data;
                $('#clienteId').val(cliente.id);
                $('#nombres').val(cliente.nombres);
                $('#apellido_paterno').val(cliente.apellido_paterno);
                $('#apellido_materno').val(cliente.apellido_materno);
                $('#correo_electronico').val(cliente.correo_electronico);
                $('#domicilio').val(cliente.domicilio);
                $('#estatus').val(cliente.estatus);
            } else {
                showAlert('error', 'Error', response.message);
            }
        },
        error: function(xhr, status, error) {
            hideSpinner();
            console.error('Error cargando cliente:', error);
            showAlert('error', 'Error', 'Error de conexión al servidor');
        }
    });
}

// Guardar cliente
function saveCliente() {
    const form = document.getElementById('clienteForm');
    const formData = new FormData(form);
    const id = $('#clienteId').val();
    
    formData.append('action', id ? 'update' : 'create');
    
    $.ajax({
        url: 'ajax/clientes.php',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                showAlert('success', '¡Éxito!', response.message);
                $('#clienteModal').modal('hide');
                loadClientes();
            } else {
                if (response.errors) {
                    showValidationErrors(response.errors);
                } else {
                    showAlert('error', 'Error', response.message);
                }
            }
        },
        error: function() {
            showAlert('error', 'Error', 'Error de conexión al servidor');
        }
    });
}

// Editar cliente
function editCliente(id) {
    showClienteModal(id);
}

// Eliminar cliente
function deleteCliente(id) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: 'Esta acción no se puede deshacer',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: 'ajax/clientes.php',
                method: 'POST',
                data: {
                    action: 'delete',
                    id: id
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        showAlert('success', '¡Eliminado!', response.message);
                        loadClientes();
                    } else {
                        showAlert('error', 'Error', response.message);
                    }
                },
                error: function() {
                    showAlert('error', 'Error', 'Error de conexión al servidor');
                }
            });
        }
    });
}

// Cerrar sesión
function logout() {
    Swal.fire({
        title: '¿Cerrar sesión?',
        text: 'Se cerrará tu sesión actual',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, cerrar sesión',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: 'ajax/clientes.php',
                method: 'POST',
                data: {
                    action: 'logout'
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        window.location.href = 'login.php';
                    }
                },
                error: function() {
                    window.location.href = 'login.php';
                }
            });
        }
    });
}

// Validar campo de formulario
function validateField(field) {
    const fieldName = field.attr('name');
    const fieldValue = field.val().trim();
    let isValid = true;
    let errorMessage = '';
    
    // Limpiar estado anterior
    field.removeClass('is-invalid is-valid');
    field.next('.invalid-feedback').text('');
    
    // Validaciones específicas
    switch (fieldName) {
        case 'nombres':
            if (fieldValue.length < 2) {
                isValid = false;
                errorMessage = 'El nombre debe tener al menos 2 caracteres';
            }
            break;
        case 'apellido_paterno':
        case 'apellido_materno':
            if (fieldValue.length < 2) {
                isValid = false;
                errorMessage = 'El apellido debe tener al menos 2 caracteres';
            }
            break;
        case 'correo_electronico':
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(fieldValue)) {
                isValid = false;
                errorMessage = 'Ingresa un correo electrónico válido';
            }
            break;
        case 'domicilio':
            if (fieldValue.length < 10) {
                isValid = false;
                errorMessage = 'El domicilio debe tener al menos 10 caracteres';
            }
            break;
    }
    
    // Aplicar clase de validación
    if (fieldValue && !isValid) {
        field.addClass('is-invalid');
        field.next('.invalid-feedback').text(errorMessage);
    } else if (fieldValue) {
        field.addClass('is-valid');
    }
    
    return isValid;
}

// Limpiar validación de campos
function clearValidation() {
    $('#clienteForm .form-control').removeClass('is-invalid is-valid');
    $('#clienteForm .invalid-feedback').text('');
}

// Mostrar errores de validación
function showValidationErrors(errors) {
    clearValidation();
    
    errors.forEach(error => {
        showAlert('error', 'Error de validación', error);
    });
}

// Mostrar alerta con SweetAlert2
function showAlert(type, title, message) {
    const config = {
        title: title,
        text: message,
        icon: type,
        timer: 3000,
        showConfirmButton: false,
        toast: true,
        position: 'top-end'
    };
    
    Swal.fire(config);
}

// Escapar HTML para evitar XSS
function escapeHtml(text) {
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    
    return text.replace(/[&<>"']/g, function(m) { return map[m]; });
}

// Formatear fecha en español
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('es-ES', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
}