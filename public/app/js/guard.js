// public/app/js/guard.js
(() => {
  const role = (sessionStorage.getItem('role') || '').toLowerCase();
  const page = window.location.pathname.split('/').pop();

  // Si no hay sesión, obligar a login.html
  if (!role) {
    if (!['login.html','register.html'].includes(page)) {
      return location.replace('login.html');
    }
    return;
  }

  // Map de páginas permitidas por rol
  const perms = {
    admin:          ['hotels.html', 'habitaciones.html', 'servicios-admin.html'],
    recepcionista:  ['recepcion.html','cuenta.html', 'asignar-servicios.html'],
    cliente:        ['rooms.html','reservas.html'],
    cocinero:       ['insumos.html'],
    gerente:        ['reportes.html']
  };

  // Si la página no está en la lista de su rol, redirige a su home
  const allowed = perms[role] || [];
  if (page && !allowed.includes(page) && page !== 'login.html') {
    const home = allowed[0] || 'login.html';
    return location.replace(home);
  }
})();
