// public/app/js/navbar.js
// —————— PRIMERA SECCIÓN: Protección de ruta ——————
;(function() {
  const mapping = {
    'reservas.html':     ['cliente','recepcion'],
    'hotels.html':       ['admin'],
    'habitaciones.html': ['admin','recepcion'],
    'insumos.html':      ['cocina'],
    'promociones.html':  ['marketing'],
    'reportes.html':     ['gerente'],
    'servicios.html':    ['servicio']
  };
  const page  = location.pathname.split('/').pop();
  const allow = mapping[page] || [];
  const role  = (sessionStorage.getItem('role')||'').toLowerCase();

  if (allow.length && !allow.includes(role)) {
    alert('No tienes permiso para esta sección.');
    window.location = 'login.html';
    // Con return detenemos el resto de ejecución de navbar.js
    return;
  }
})();

document.addEventListener('DOMContentLoaded', () => {
  const role = (sessionStorage.getItem('role')||'').toLowerCase();
  const navbar = document.getElementById('navbar');

  // 1) Define aquí los menús por rol
  const menus = {
    cliente:   [{ label:'Reservas',     href:'reservas.html' }],
    recepcion: [{ label:'Reservas',     href:'reservas.html' }],
    admin:     [
      { label:'Hoteles',      href:'hotels.html' },
      { label:'Habitaciones', href:'habitaciones.html' },
      { label:'Servicios',    href:'servicios.html' }
    ],
    cocina:    [{ label:'Insumos',      href:'insumos.html' }],
    marketing: [{ label:'Promociones',  href:'promociones.html' }],
    gerente:   [{ label:'Reportes',     href:'reportes.html' }],
    servicio:  [{ label:'Servicios',    href:'servicios.html' }]
  };

  // 2) Monta el HTML
  let html = `
    <nav class="bg-white shadow">
      <div class="container mx-auto px-6 py-4 flex justify-between items-center">
        <a href="#" class="text-xl font-bold text-indigo-600">Veranum</a>
        <div class="space-x-4">
  `;
  (menus[role] || []).forEach(item => {
    html += `<a href="${item.href}"
                 class="text-gray-700 hover:text-gray-900">
               ${item.label}
             </a>`;
  });
  html += `
          <button id="logout"
                  class="ml-4 text-red-600 hover:text-red-800">
            Cerrar sesión
          </button>
        </div>
      </div>
    </nav>
  `;
  navbar.innerHTML = html;

  // 3) Logout
  document.getElementById('logout').onclick = () => {
    sessionStorage.clear();
    window.location = 'login.html';
  };
});
