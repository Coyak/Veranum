// public/app/js/navbar.js
document.addEventListener('DOMContentLoaded', () => {
  const role = (sessionStorage.getItem('role')||'').toLowerCase();
  const nav  = document.getElementById('navbar');
  let html = `
    <nav class="bg-black bg-opacity-95 shadow-md py-4 px-8 flex flex-col md:flex-row md:justify-between md:items-center">
      <div class="text-2xl font-bold tracking-wide mb-2 md:mb-0" style="color: #bfa14a;">Veranum Hotel</div>
      <div class="flex flex-col md:flex-row w-full md:w-auto gap-2 md:gap-0 md:items-center md:justify-end">`;

  switch(role) {
    case 'admin':
      html += `
        <a href="hotels.html" class="font-semibold md:mx-2 hover:text-yellow-300 w-full md:w-auto text-center" style="color: #bfa14a;">Hoteles</a>
        <a href="habitaciones.html" class="font-semibold md:mx-2 hover:text-yellow-300 w-full md:w-auto text-center" style="color: #bfa14a;">Habitaciones</a>
        <a href="servicios-admin.html" class="font-semibold md:mx-2 hover:text-yellow-300 w-full md:w-auto text-center" style="color: #bfa14a;">Gestionar Servicios</a>`;
      break;
    case 'recepcionista':
      html += `
        <a href="recepcion.html" class="font-semibold md:mx-2 hover:text-yellow-300 w-full md:w-auto text-center" style="color: #bfa14a;">Recepción</a>`;
      break;
    case 'cliente':
      html += `
        <a href="rooms.html" class="font-semibold md:mx-2 hover:text-yellow-300 w-full md:w-auto text-center" style="color: #bfa14a;">Habitaciones</a>
        <a href="reservas.html" class="font-semibold md:mx-2 hover:text-yellow-300 w-full md:w-auto text-center" style="color: #bfa14a;">Reservas</a>`;
      break;
    case 'cocinero':
      html += `
        <a href="insumos.html" class="font-semibold md:mx-2 hover:text-yellow-300 w-full md:w-auto text-center" style="color: #bfa14a;">Insumos</a>
        <a href="movimientos-insumo.html" class="font-semibold md:mx-2 hover:text-yellow-300 w-full md:w-auto text-center" style="color: #bfa14a;">Movimientos de Insumo</a>`;
      break;
    case 'gerente':
      html += `
        <a href="reportes.html" class="font-semibold md:mx-2 hover:text-yellow-300 w-full md:w-auto text-center" style="color: #bfa14a;">Ocupación</a>
        <a href="ingresos-totales.html" class="font-semibold md:mx-2 hover:text-yellow-300 w-full md:w-auto text-center" style="color: #bfa14a;">Ingresos Totales</a>`;
      break;
    default:
      html += ''; // ningún link si rol desconocido
  }

  html += `
        <button id="logout" class="md:ml-4 px-4 py-2 rounded-lg font-semibold transition border-2 border-black hover:opacity-90 w-full md:w-auto text-center" style="background-color: #bfa14a; color: #222;">
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
