// assets/js/index.js
// Funciones para el menú móvil
function toggleSidebar() {
  const sidebar = document.getElementById("sidebar");
  const overlay = document.querySelector(".sidebar-overlay");

  sidebar.classList.toggle("show");
  overlay.classList.toggle("show");
}

// Función para mostrar/ocultar spinner
function showSpinner() {
  document.querySelector(".loading-spinner").style.display = "block";
}

function hideSpinner() {
  document.querySelector(".loading-spinner").style.display = "none";
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

  // Cerrar menú móvil si está abierto
  if (window.innerWidth <= 768) {
    toggleSidebar();
  }
}
