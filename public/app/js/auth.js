// public/app/js/auth.js

document.addEventListener('DOMContentLoaded', () => {
  // 1) Localiza el formulario
  const form = document.getElementById('login-form');
  if (!form) {
    // Si no está presente, no hacemos nada
    console.warn('auth.js: No se encontró <form id="login-form">');
    return;
  }

  // 2) Engancha el onsubmit
  form.onsubmit = async e => {
    e.preventDefault();

    // 3) Recoge credenciales
    const email    = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value;

    // 4) Llama a la API
    let resp;
    try {
      resp = await API.call('login', { email, password });
    } catch (err) {
      return alert('Error de red. Intenta de nuevo.');
    }

    // 5) Valida la respuesta
    if (!resp.ok) {
      return alert('Credenciales inválidas');
    }

    // 6) Guarda el rol (en minúsculas) y el user_id si quieres
    const role = (resp.role || '').toLowerCase();
    sessionStorage.setItem('role', role);
    if (resp.user_id) {
      sessionStorage.setItem('user_id', resp.user_id);
    }

    // 7) Redirige según rol
    switch (role) {
      case 'cliente':
        window.location = 'reservas.html';
        break;
      case 'recepcion':
        window.location = 'reservas.html';
        break;
      case 'admin':
        window.location = 'hotels.html';
        break;
      case 'cocina':
        window.location = 'insumos.html';
        break;
      case 'marketing':
        window.location = 'promociones.html';
        break;
      case 'gerente':
        window.location = 'reportes.html';
        break;
      case 'servicio':
        window.location = 'servicios.html';
        break;
      default:
        // En caso de rol inesperado, vuelve al login
        alert('Rol no reconocido.');
        window.location = 'login.html';
    }
  };
});
