document.addEventListener('DOMContentLoaded', () => {
  guard.only(['gerente']);
  cargarOcupacion();
  cargarIngresos();

  async function cargarOcupacion() {
    const res = await API.call('reporte-ocupacion');
    const tabla = document.getElementById('tabla-ocupacion');
    if (!res || !Array.isArray(res)) {
      tabla.innerHTML = '<tr><td colspan="2">No se pudo cargar la ocupación</td></tr>';
      return;
    }
    tabla.innerHTML = '';
    res.forEach((hab, idx) => {
      const zebra = idx % 2 === 0 ? 'bg-white' : 'bg-gray-50';
      let estadoHtml = hab.ocupada
        ? `<span class='inline-flex items-center text-red-600 font-bold'><svg class='w-4 h-4 mr-1' fill='none' stroke='currentColor' stroke-width='2' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' d='M6 18L18 6M6 6l12 12'/></svg>Ocupada</span>`
        : `<span class='inline-flex items-center text-green-600 font-bold'><svg class='w-4 h-4 mr-1' fill='none' stroke='currentColor' stroke-width='2' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' d='M5 13l4 4L19 7'/></svg>Libre</span>`;
      tabla.innerHTML += `
        <tr class="${zebra}">
          <td class="px-4 py-2 border-b">${hab.nombre || hab.numero || hab.id}</td>
          <td class="px-4 py-2 border-b">${estadoHtml}</td>
        </tr>
      `;
    });
  }

  async function cargarIngresos() {
    const res = await API.call('reporte-ingresos');
    const tabla = document.getElementById('tabla-ingresos');
    if (!res) {
      tabla.innerHTML = '<tr><td colspan="2">No se pudo cargar los ingresos</td></tr>';
      return;
    }
    tabla.innerHTML = `
      <tr class="bg-white font-bold">
        <td class="px-4 py-2 border-b">Total general</td>
        <td class="px-4 py-2 border-b">$${res.total_general ?? 0}</td>
      </tr>
      <tr class="bg-gray-50">
        <td class="px-4 py-2 border-b">Total por habitaciones</td>
        <td class="px-4 py-2 border-b">$${res.total_habitaciones ?? 0}</td>
      </tr>
      <tr class="bg-white">
        <td class="px-4 py-2 border-b">Total por servicios</td>
        <td class="px-4 py-2 border-b">$${res.total_servicios ?? 0}</td>
      </tr>
    `;
  }
}); 