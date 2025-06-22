// public/app/js/auth.js
console.log('auth.js cargado');
// public/app/js/auth.js
document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('login-form');
  form.onsubmit = async e => {
    e.preventDefault();

    const email    = form.email.value.trim();
    const password = form.password.value;

    try {
      const resp = await API.call('login', { email, password });
      if (!resp.ok) {
        return alert(resp.error || 'Credenciales inválidas');
      }

      // Guardo el rol en sessionStorage
      const role = (resp.role || '').toLowerCase();
      sessionStorage.setItem('role', role);

      // Redirijo según rol
      switch (role) {
        case 'admin':
          window.location = 'hotels.html';
          break;
        case 'recepcionista':
          window.location = 'recepcion.html';
          break;
        case 'cliente':
          window.location = 'reservas.html';
          break;
        case 'cocinero':
          window.location = 'insumos.html';
          break;
        case 'gerente':
          window.location = 'reportes.html';
          break;
        default:
          // Por si acaso
          window.location = 'login.html';
      }

    } catch (err) {
      console.error(err);
      alert('Error de red al iniciar sesión');
    }
  };
});

