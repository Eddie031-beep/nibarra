/**
 * NIBARRA - Sistema de Mantenimiento
 * Archivo: public/assets/js/app.js
 * Funcionalidades globales y utilidades
 */

// ============================================
// PROTECCIÓN CONTRA COPIA (Requisito D)
// ============================================

document.addEventListener('DOMContentLoaded', function() {
  // Deshabilitar clic derecho
  document.addEventListener('contextmenu', e => {
    e.preventDefault();
    showToast('⚠️ Contenido protegido', 'warning');
    return false;
  });
  
  // Deshabilitar atajos de teclado
  document.addEventListener('keydown', e => {
    // Ctrl+U (ver código fuente)
    // Ctrl+S (guardar página)
    // Ctrl+C (copiar) - solo en elementos no input
    // Ctrl+P (imprimir)
    // F12 (DevTools)
    if (e.ctrlKey && ['u', 's', 'p'].includes(e.key.toLowerCase())) {
      e.preventDefault();
      showToast('⚠️ Acción no permitida', 'warning');
      return false;
    }
    
    if (e.key === 'F12' || (e.ctrlKey && e.shiftKey && e.key === 'I')) {
      e.preventDefault();
      return false;
    }
  });
  
  // Detectar DevTools (solo advertencia en consola)
  const devtools = /./;
  devtools.toString = function() {
    this.opened = true;
  };
  
  console.log('%c🔒 NIBARRA - Sistema Protegido', 'font-size:20px;color:#3b82f6;font-weight:bold');
  console.log('%c⚠️ Este sistema está protegido. El acceso no autorizado está prohibido.', 'font-size:14px;color:#ef4444');
  console.log('%c', devtools);
  
  // Marca de agua en consola
  setInterval(() => {
    console.clear();
    console.log('%c🔒 NIBARRA', 'font-size:16px;color:#3b82f6;font-weight:bold');
  }, 5000);
});

// ============================================
// SISTEMA DE NOTIFICACIONES (TOAST)
// ============================================

let toastContainer = null;

function showToast(message, type = 'info', duration = 3000) {
  // Crear contenedor si no existe
  if (!toastContainer) {
    toastContainer = document.createElement('div');
    toastContainer.id = 'toastContainer';
    toastContainer.style.cssText = `
      position: fixed;
      top: 20px;
      right: 20px;
      z-index: 10000;
      display: flex;
      flex-direction: column;
      gap: 10px;
      pointer-events: none;
    `;
    document.body.appendChild(toastContainer);
  }
  
  // Crear toast
  const toast = document.createElement('div');
  toast.className = `toast toast-${type}`;
  
  const colors = {
    success: '#10b981',
    error: '#ef4444',
    warning: '#f59e0b',
    info: '#3b82f6'
  };
  
  const icons = {
    success: '✓',
    error: '✕',
    warning: '⚠',
    info: 'ℹ'
  };
  
  toast.style.cssText = `
    background: var(--bg-card);
    border: 1px solid ${colors[type]};
    border-left: 4px solid ${colors[type]};
    color: var(--text-primary);
    padding: 1rem 1.5rem;
    border-radius: 0.5rem;
    box-shadow: var(--shadow-lg);
    min-width: 250px;
    max-width: 400px;
    animation: slideIn 0.3s ease;
    pointer-events: auto;
    display: flex;
    align-items: center;
    gap: 0.75rem;
  `;
  
  toast.innerHTML = `
    <div style="
      width: 24px;
      height: 24px;
      border-radius: 50%;
      background: ${colors[type]};
      color: white;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: bold;
      flex-shrink: 0;
    ">${icons[type]}</div>
    <div style="flex: 1">${message}</div>
    <button onclick="this.parentElement.remove()" style="
      background: none;
      border: none;
      color: var(--text-secondary);
      cursor: pointer;
      font-size: 1.2rem;
      padding: 0;
      width: 20px;
      height: 20px;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    ">×</button>
  `;
  
  toastContainer.appendChild(toast);
  
  // Auto-eliminar
  setTimeout(() => {
    toast.style.animation = 'slideOut 0.3s ease';
    setTimeout(() => toast.remove(), 300);
  }, duration);
}

// Animaciones CSS para toast
const toastStyles = document.createElement('style');
toastStyles.textContent = `
  @keyframes slideIn {
    from {
      transform: translateX(400px);
      opacity: 0;
    }
    to {
      transform: translateX(0);
      opacity: 1;
    }
  }
  
  @keyframes slideOut {
    from {
      transform: translateX(0);
      opacity: 1;
    }
    to {
      transform: translateX(400px);
      opacity: 0;
    }
  }
`;
document.head.appendChild(toastStyles);

// ============================================
// UTILIDADES GLOBALES
// ============================================

// Formatear fecha
function formatDate(dateString, includeTime = false) {
  const date = new Date(dateString);
  const options = {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  };
  
  if (includeTime) {
    options.hour = '2-digit';
    options.minute = '2-digit';
  }
  
  return date.toLocaleDateString('es-PA', options);
}

// Formatear moneda
function formatCurrency(amount) {
  return new Intl.NumberFormat('es-PA', {
    style: 'currency',
    currency: 'USD'
  }).format(amount);
}

// Confirmar acción
function confirmAction(message, onConfirm) {
  if (confirm(message)) {
    onConfirm();
  }
}

// Debounce para búsquedas
function debounce(func, wait) {
  let timeout;
  return function executedFunction(...args) {
    const later = () => {
      clearTimeout(timeout);
      func(...args);
    };
    clearTimeout(timeout);
    timeout = setTimeout(later, wait);
  };
}

// Copiar al portapapeles
async function copyToClipboard(text) {
  try {
    await navigator.clipboard.writeText(text);
    showToast('✓ Copiado al portapapeles', 'success');
  } catch (err) {
    showToast('✕ Error al copiar', 'error');
  }
}

// ============================================
// INDICADOR DE CARGA GLOBAL
// ============================================

let loadingOverlay = null;

function showLoading(message = 'Cargando...') {
  if (!loadingOverlay) {
    loadingOverlay = document.createElement('div');
    loadingOverlay.id = 'loadingOverlay';
    loadingOverlay.style.cssText = `
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(0, 0, 0, 0.7);
      z-index: 9998;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-direction: column;
      gap: 1rem;
    `;
    
    loadingOverlay.innerHTML = `
      <div style="
        width: 50px;
        height: 50px;
        border: 4px solid var(--border-color);
        border-top-color: var(--primary);
        border-radius: 50%;
        animation: spin 1s linear infinite;
      "></div>
      <div id="loadingMessage" style="
        color: white;
        font-size: 1.1rem;
        font-weight: 500;
      ">${message}</div>
    `;
    
    document.body.appendChild(loadingOverlay);
  } else {
    loadingOverlay.style.display = 'flex';
    document.getElementById('loadingMessage').textContent = message;
  }
}

function hideLoading() {
  if (loadingOverlay) {
    loadingOverlay.style.display = 'none';
  }
}

// Animación de spin
const spinStyles = document.createElement('style');
spinStyles.textContent = `
  @keyframes spin {
    to { transform: rotate(360deg); }
  }
`;
document.head.appendChild(spinStyles);

// ============================================
// VALIDACIÓN DE FORMULARIOS
// ============================================

function validateForm(formId) {
  const form = document.getElementById(formId);
  if (!form) return false;
  
  const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
  let isValid = true;
  
  inputs.forEach(input => {
    if (!input.value.trim()) {
      input.style.borderColor = 'var(--danger)';
      isValid = false;
      
      input.addEventListener('input', function() {
        this.style.borderColor = '';
      }, { once: true });
    }
  });
  
  if (!isValid) {
    showToast('⚠️ Por favor completa todos los campos requeridos', 'warning');
  }
  
  return isValid;
}

// ============================================
// MANEJO DE ERRORES GLOBAL
// ============================================

window.addEventListener('error', function(e) {
  console.error('Error global:', e.error);
  // Solo mostrar en desarrollo
  if (window.location.hostname === 'localhost') {
    showToast('⚠️ Error: ' + e.message, 'error', 5000);
  }
});

// ============================================
// DETECTAR NAVEGADOR Y SISTEMA
// ============================================

const userAgent = navigator.userAgent.toLowerCase();
const browserInfo = {
  isChrome: userAgent.includes('chrome') && !userAgent.includes('edge'),
  isFirefox: userAgent.includes('firefox'),
  isSafari: userAgent.includes('safari') && !userAgent.includes('chrome'),
  isEdge: userAgent.includes('edge'),
  isMobile: /android|webos|iphone|ipad|ipod|blackberry|iemobile|opera mini/i.test(userAgent)
};

// Advertencia para navegadores no soportados
if (browserInfo.isEdge && parseInt(userAgent.match(/edge\/(\d+)/)[1]) < 79) {
  showToast('⚠️ Tu navegador puede no ser compatible. Usa Chrome, Firefox o Edge moderno', 'warning', 10000);
}

// ============================================
// ESTADÍSTICAS DE USO (opcional)
// ============================================

const pageLoadTime = performance.now();
window.addEventListener('load', function() {
  const loadTime = Math.round(performance.now() - pageLoadTime);
  console.log(`📊 Página cargada en ${loadTime}ms`);
});

// ============================================
// EXPORTAR FUNCIONES GLOBALES
// ============================================

window.nibarra = {
  showToast,
  showLoading,
  hideLoading,
  formatDate,
  formatCurrency,
  confirmAction,
  debounce,
  copyToClipboard,
  validateForm,
  browserInfo
};

// ============================================
// MARCAR ENLACES ACTIVOS EN NAVEGACIÓN
// ============================================

document.addEventListener('DOMContentLoaded', function() {
  const currentPath = window.location.pathname;
  const navLinks = document.querySelectorAll('.nav-links a');
  
  navLinks.forEach(link => {
    if (link.getAttribute('href') === currentPath) {
      link.classList.add('active');
    }
  });
});

// ============================================
// CONFIRMACIÓN AL SALIR SI HAY CAMBIOS
// ============================================

let formChanged = false;
document.addEventListener('DOMContentLoaded', function() {
  const forms = document.querySelectorAll('form');
  forms.forEach(form => {
    form.addEventListener('change', () => formChanged = true);
    form.addEventListener('submit', () => formChanged = false);
  });
});

window.addEventListener('beforeunload', function(e) {
  if (formChanged) {
    e.preventDefault();
    e.returnValue = '¿Estás seguro de salir? Los cambios no guardados se perderán.';
    return e.returnValue;
  }
});

console.log('✓ Nibarra System Loaded');