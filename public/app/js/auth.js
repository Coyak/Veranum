// public/app/js/auth.js
console.log('auth.js cargado');
// public/app/js/auth.js
document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('login-form');
  if (form) {
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
        sessionStorage.setItem('user_id', resp.id);

        // Redirijo según rol
        switch (role) {
          case 'admin':
            window.location = 'hotels.html';
            break;
          case 'recepcionista':
            window.location = 'recepcion.html';
            break;
          case 'cliente':
            window.location = 'rooms.html';
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
  }

  // Manejo del formulario de registro
  const registerForm = document.getElementById('form-register');
  if (registerForm) {
    registerForm.onsubmit = async e => {
      e.preventDefault();

      const nombre = registerForm.nombre.value.trim();
      const email = registerForm.email.value.trim();
      const password = registerForm.password.value;

      // Validaciones básicas
      if (!nombre || !email || !password) {
        showRegisterError('Todos los campos son requeridos');
        return;
      }

      if (password.length < 6) {
        showRegisterError('La contraseña debe tener al menos 6 caracteres');
        return;
      }

      try {
        const resp = await API.call('register', { nombre, email, password });
        if (!resp.ok) {
          showRegisterError(resp.error || 'Error al crear la cuenta');
          return;
        }

        // Registro exitoso
        alert('Cuenta creada exitosamente. Por favor inicia sesión.');
        window.location = 'login.html';

      } catch (err) {
        console.error(err);
        showRegisterError('Error de red al crear la cuenta');
      }
    };
  }

  // Función para mostrar errores en el registro
  function showRegisterError(message) {
    const errorElement = document.getElementById('error-register');
    if (errorElement) {
      errorElement.textContent = message;
      errorElement.classList.remove('hidden');
    } else {
      alert(message);
    }
  }
});

