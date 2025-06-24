// public/app/js/guard.js
window.guard = {
  only: function(roles) {
    const role = (sessionStorage.getItem('role') || '').toLowerCase();
    const page = window.location.pathname.split('/').pop();

    // Si no hay sesión, obligar a login.html
    if (!role) {
      if (!['login.html','register.html'].includes(page)) {
        return location.replace('login.html');
      }
      return;
    }

    // Si el rol no está permitido, redirige a login
    if (!roles.includes(role)) {
      return location.replace('login.html');
    }
  }
};

// Protección automática por página (opcional, puedes quitar si solo usas guard.only manualmente)
(function() {
  const role = (sessionStorage.getItem('role') || '').toLowerCase();
  const page = window.location.pathname.split('/').pop();
  const perms = {
    admin:          ['hotels.html', 'habitaciones.html', 'servicios-admin.html'],
    recepcionista:  ['recepcion.html','cuenta.html', 'asignar-servicios.html'],
    cliente:        ['rooms.html','reservas.html'],
    cocinero:       ['insumos.html', 'movimientos-insumo.html'],
    gerente:        ['reportes.html']
  };
  const allowed = perms[role] || [];
  if (page && !allowed.includes(page) && page !== 'login.html') {
    const home = allowed[0] || 'login.html';
    return location.replace(home);
  }
})();
