// public/app/js/recepcion.js
document.addEventListener('DOMContentLoaded', async () => {
  const tbody = document.getElementById('res-body');

  // Carga reservas con status "reservada"
  async function load() {
    const list = await API.call('reservas', {}, { method: 'GET' });
    tbody.innerHTML = '';
    list.forEach(r => {
      if (r.status !== 'reservada') return;
      tbody.innerHTML += `
        <tr class="hover:bg-gray-50">
          <td class="px-6 py-3">${r.id}</td>
          <td class="px-6 py-3">${r.cliente_nombre}</td>
          <td class="px-6 py-3">${r.hab_nombre}</td>
          <td class="px-6 py-3">${r.fecha_inicio}</td>
          <td class="px-6 py-3 text-right space-x-2">
            <button data-id="${r.id}" class="checkin bg-green-500 text-white px-2 py-1 rounded">
              Check-In
            </button>
            <button data-id="${r.id}" class="servicio bg-blue-500 text-white px-2 py-1 rounded">
              Servicio Extra
            </button>
          </td>
        </tr>`;
    });

    // Eventos de botones
    document.querySelectorAll('.checkin').forEach(btn => {
      btn.onclick = async () => {
        if (!confirm('Confirmar llegada?')) return;
        const resp = await API.call('reserva-checkin', { id: btn.dataset.id });
        if (resp.ok) load(); else alert('Error al hacer check-in');
      };
    });
    document.querySelectorAll('.servicio').forEach(btn => {
      btn.onclick = () => {
        const serv = prompt('¿Qué servicio extra?');
        if (!serv) return;
        API.call('reserva-servicio', { id: btn.dataset.id, servicio: serv })
           .then(r => r.ok ? alert('Notificado') : alert('Error al notificar'));
      };
    });
  }

  load();
});
