// public/app/js/navbar.js
document.addEventListener('DOMContentLoaded', () => {
  const role = (sessionStorage.getItem('role')||'').toLowerCase();
  const nav  = document.getElementById('navbar');
  let html = `
    <nav class="bg-white shadow px-6 py-4 flex justify-between items-center">
      <div class="text-2xl font-bold">Veranum</div>
      <div class="space-x-4">`;

  switch(role) {
    case 'admin':
      html += `
        <a href="hotels.html" class="hover:underline">Hoteles</a>
        <a href="habitaciones.html" class="hover:underline">Habitaciones</a>
        <a href="servicios-admin.html" class="hover:underline">Gestionar Servicios</a>`;
      break;
    case 'recepcionista':
      html += `
        <a href="recepcion.html" class="hover:underline">Recepción</a>`;
      break;
    case 'cliente':
      html += `
        <a href="rooms.html" class="hover:underline">Habitaciones</a>
        <a href="reservas.html" class="hover:underline">Reservas</a>`;
      break;
    case 'cocinero':
      html += `
        <a href="insumos.html" class="hover:underline">Insumos</a>
        <a href="movimientos-insumo.html" class="hover:underline">Movimientos de Insumo</a>`;
      break;
    case 'gerente':
      html += `
        <a href="reportes.html" class="hover:underline">Ocupación</a>
        <a href="ingresos-totales.html" class="hover:underline">Ingresos Totales</a>`;
      break;
    default:
      html += ''; // ningún link si rol desconocido
  }

  html += `
        <button id="logout" class="ml-4 bg-red-500 text-white px-3 py-1 rounded">
          Cerrar sesión
        </button>
      </div>
    </nav>`;

  nav.innerHTML = html;
  document.getElementById('logout').onclick = () => {
    API.call('logout', {});
    sessionStorage.clear();
    location = 'login.html';
  };
});
