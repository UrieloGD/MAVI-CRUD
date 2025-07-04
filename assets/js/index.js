// assets/js/index.js
// Funciones para el menú móvil
function toggleSidebar() {
  const sidebar = document.getElementById("sidebar");
  const overlay = document.querySelector(".sidebar-overlay");

  sidebar.classList.toggle("show");
  overlay.classList.toggle("show");
}

// Función para mostrar/ocultar el botón de limpiar búsqueda
function toggleSearchClear() {
  const searchInput = document.getElementById("searchInput");
  const clearButton = document.querySelector(".search-clear");

  if (searchInput.value.length > 0) {
    clearButton.style.display = "block";
  } else {
    clearButton.style.display = "none";
  }
}

// Función para limpiar la búsqueda
function clearSearch() {
  const searchInput = document.getElementById("searchInput");
  searchInput.value = "";
  toggleSearchClear();
  // Aquí se puede agregar lógica para actualizar la tabla
}

// Función para mostrar/ocultar spinner
function showSpinner() {
  document.querySelector(".loading-spinner").style.display = "block";
}

function hideSpinner() {
  document.querySelector(".loading-spinner").style.display = "none";
}

// Función para actualizar breadcrumb
function updateBreadcrumb(text) {
  document.getElementById("breadcrumb-current").textContent = text;
}

// Función para mostrar secciones mejorada
function showSection(section) {
  // Ocultar todas las secciones
  document.querySelectorAll('[id$="-section"]').forEach((el) => {
    el.style.display = "none";
  });

  // Mostrar la sección seleccionada
  document.getElementById(section + "-section").style.display = "block";

  // Actualizar navegación
  document.querySelectorAll(".nav-link").forEach((link) => {
    link.classList.remove("active");
  });
  document.querySelector(`[data-section="${section}"]`).classList.add("active");

  // Actualizar breadcrumb
  const breadcrumbText =
    section === "dashboard" ? "Dashboard" : "Gestión de Clientes";
  updateBreadcrumb(breadcrumbText);

  // Cerrar menú móvil si está abierto
  if (window.innerWidth <= 768) {
    toggleSidebar();
  }
}

// Event listeners
document.addEventListener("DOMContentLoaded", function () {
  // Búsqueda en tiempo real
  const searchInput = document.getElementById("searchInput");
  searchInput.addEventListener("input", toggleSearchClear);

  // Navegación
  document.querySelectorAll(".nav-link").forEach((link) => {
    link.addEventListener("click", function (e) {
      e.preventDefault();
      const section = this.getAttribute("data-section");
      showSection(section);
    });
  });

  // Cerrar menú al hacer clic en overlay
  document
    .querySelector(".sidebar-overlay")
    .addEventListener("click", toggleSidebar);

  // Cerrar menú al redimensionar ventana
  window.addEventListener("resize", function () {
    if (window.innerWidth > 768) {
      document.getElementById("sidebar").classList.remove("show");
      document.querySelector(".sidebar-overlay").classList.remove("show");
    }
  });
});
