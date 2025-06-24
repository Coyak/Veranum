document.addEventListener('DOMContentLoaded', () => {
  guard.only(['cocinero']);
  cargarMovimientos();

  async function cargarMovimientos() {
    const res = await API.call('insumo-movimientos');
    const tabla = document.getElementById('tabla-movimientos');
    if (!res || !Array.isArray(res)) {
      tabla.innerHTML = '<tr><td colspan="6">No se pudo cargar el historial</td></tr>';
      return;
    }
    tabla.innerHTML = '';
    res.forEach(mov => {
      const fecha = mov.fecha ? new Date(mov.fecha).toLocaleString() : '';
      const tipo = mov.tipo_movimiento === 'ingreso' ? 'Ingreso' : 'Consumo';
      tabla.innerHTML += `
        <tr>
          <td class="border px-2 py-1">${fecha}</td>
          <td class="border px-2 py-1">${mov.insumo_nombre}</td>
          <td class="border px-2 py-1">${mov.unidad}</td>
          <td class="border px-2 py-1">${tipo}</td>
          <td class="border px-2 py-1">${mov.cantidad}</td>
          <td class="border px-2 py-1">${mov.observacion || ''}</td>
        </tr>
      `;
    });
  }
}); 