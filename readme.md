# Sistema de Gestión de Clientes

Sistema CRUD completo desarrollado en PHP con programación orientada a objetos para la gestión de clientes.

## Características

- **Sistema de autenticación** con sesiones seguras
- **CRUD completo** para gestión de clientes
- **Interfaz responsiva** con Bootstrap 5
- **Operaciones Ajax** para mejor experiencia de usuario
- **Validación** tanto en cliente como en servidor
- **Búsqueda en tiempo real**
- **Notificaciones** con SweetAlert2
- **Arquitectura orientada a objetos**

## Tecnologías Utilizadas

- PHP 8+
- MySQL
- Bootstrap 5
- jQuery
- SweetAlert2
- PDO para base de datos

## Instalación

1. **Clonar el repositorio**
   ```bash
   git clone [url-del-repositorio]
   cd sistema-clientes
   Configurar la base de datos

Crear una base de datos MySQL
Importar el archivo database.sql
Configurar las credenciales en config/database.php


Configurar el servidor web

Asegurar que PHP 8+ esté instalado
Configurar el documento root hacia la carpeta del proyecto
Habilitar la extensión PDO MySQL


Acceder al sistema

Abrir el navegador y navegar al proyecto
Usar las credenciales de prueba:

Usuario: admin
Contraseña: admin123





Estructura del Proyecto
/proyecto
├── config/
│   └── database.php          # Configuración de BD
├── classes/
│   ├── Database.php          # Clase para conexión PDO
│   ├── Cliente.php           # Modelo Cliente
│   └── Auth.php              # Clase de autenticación
├── assets/
│   ├── css/
│   │   └── custom.css        # Estilos personalizados
│   └── js/
│       └── main.js           # JavaScript principal
├── ajax/
│   └── clientes.php          # Controlador Ajax
├── login.php                 # Página de login
├── index.php                 # Dashboard principal
├── database.sql              # Script de base de datos
├── .htaccess                 # Configuración Apache
└── README.md                 # Documentación

Funcionalidades
Autenticación

Login seguro con validación de credenciales
Gestión de sesiones con timeout
Logout seguro
Protección de rutas

Gestión de Clientes

Crear: Agregar nuevos clientes con validación
Leer: Listar clientes con paginación y búsqueda
Actualizar: Modificar datos de clientes existentes
Eliminar: Remover clientes con confirmación

Validaciones

Validación de campos requeridos
Validación de formato de email
Validación de longitud de campos
Verificación de email único
Validación en tiempo real (JavaScript)
Validación en servidor (PHP)

Interfaz de Usuario

Diseño responsivo con Bootstrap 5
Sidebar de navegación
Modales para formularios
Tablas con acciones inline
Búsqueda en tiempo real
Notificaciones con SweetAlert2

Seguridad

Preparación de consultas SQL (PDO)
Validación y sanitización de datos
Protección contra XSS
Gestión segura de sesiones
Headers de seguridad HTTP
Protección de archivos de configuración