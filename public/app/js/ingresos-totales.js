document.addEventListener('DOMContentLoaded', () => {
  guard.only(['gerente']);
  const form = document.getElementById('filtro-fechas');
  const btnLimpiar = document.getElementById('btn-limpiar');
  const inputInicio = document.getElementById('fecha-inicio');
  const inputFin = document.getElementById('fecha-fin');

  form.onsubmit = (e) => {
    e.preventDefault();
    cargarIngresos(inputInicio.value, inputFin.value);
  };
  btnLimpiar.onclick = () => {
    inputInicio.value = '';
    inputFin.value = '';
    cargarIngresos();
  };

  cargarIngresos();

  async function cargarIngresos(fechaInicio, fechaFin) {
    let params = {};
    if (fechaInicio) params.fecha_inicio = fechaInicio;
    if (fechaFin) params.fecha_fin = fechaFin;
    const res = await API.call('reporte-ingresos', params);
    const tabla = document.getElementById('tabla-ingresos');
    if (!res) {
      tabla.innerHTML = '<tr><td colspan="2">No se pudo cargar los ingresos</td></tr>';
      return;
    }
    // Total general
    let html = `
      <tr class="bg-white font-bold">
        <td class="px-6 py-3 border-b">Total general</td>
        <td class="px-6 py-3 border-b">$${res.total_general ?? 0}</td>
      </tr>
    `;
    tabla.innerHTML = html;

    // Tabla de habitaciones
    let habHtml = '';
    if (Array.isArray(res.detalle_habitaciones) && res.detalle_habitaciones.length > 0) {
      habHtml += `<tr><td colspan="2" class="pt-6 pb-2 text-lg font-semibold text-gray-700">Habitaciones</td></tr>`;
      res.detalle_habitaciones.forEach((h, idx) => {
        const zebra = idx % 2 === 0 ? 'bg-white' : 'bg-gray-50';
        habHtml += `
          <tr class="${zebra}">
            <td class="px-6 py-2 border-b">${h.habitacion}</td>
            <td class="px-6 py-2 border-b">$${h.monto}</td>
          </tr>
        `;
      });
    }
    // Tabla de servicios
    let servHtml = '';
    if (Array.isArray(res.detalle_servicios) && res.detalle_servicios.length > 0) {
      servHtml += `<tr><td colspan="2" class="pt-6 pb-2 text-lg font-semibold text-gray-700">Servicios</td></tr>`;
      res.detalle_servicios.forEach((s, idx) => {
        const zebra = idx % 2 === 0 ? 'bg-white' : 'bg-gray-50';
        servHtml += `
          <tr class="${zebra}">
            <td class="px-6 py-2 border-b">${s.servicio}</td>
            <td class="px-6 py-2 border-b">$${s.monto}</td>
          </tr>
        `;
      });
    }
    tabla.innerHTML += habHtml + servHtml;
  }
}); 