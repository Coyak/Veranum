// public/app/js/habitaciones.js
document.addEventListener('DOMContentLoaded', async () => {
  const hotelMap = {};
  const tbody    = document.getElementById('habitacion-tbody');
  const btnNew   = document.getElementById('btn-new');
  const formCont = document.getElementById('form-container');
  const form     = document.getElementById('habitacion-form');
  const selHotel = document.getElementById('form-hotel');
  const inpNom   = document.getElementById('form-nombre');
  const inpPre   = document.getElementById('form-precio');
  const btnCancel= document.getElementById('form-cancel');
  let editingId  = null;

  // 1) Carga hoteles → llena hotelMap y el <select>
  await loadHoteles();

  // 2) Carga habitaciones → usa hotelMap para el nombre
  await loadHabitaciones();

  // 3) Setup del formulario
  btnNew.onclick = () => {
    editingId = null;
    form.reset();
    formCont.classList.remove('hidden');
  };
  btnCancel.onclick = () => formCont.classList.add('hidden');
  form.onsubmit = async e => {
    e.preventDefault();
    const data = {
      hotel_id: selHotel.value,
      nombre:   inpNom.value.trim(),
      precio:   inpPre.value
    };
    if (editingId) data.id = editingId;
    const api = editingId ? 'habitacion-update' : 'habitacion-create';
    const resp = await API.call(api, data);
    if (resp.ok) {
      formCont.classList.add('hidden');
      await loadHabitaciones();
    } else {
      alert('Error: ' + (resp.error || 'Desconocido'));
    }
  };

  // — Funciones auxiliares —

  async function loadHoteles() {
    const listaHot = await API.call('hoteles');
    selHotel.innerHTML = '<option value="">Selecciona hotel</option>';
    listaHot.forEach(h => {
      selHotel.innerHTML += `<option value="${h.id}">${h.nombre}</option>`;
      hotelMap[h.id] = h.nombre;
    });
  }

  async function loadHabitaciones() {
    const lista = await API.call('habitaciones');
    tbody.innerHTML = '';
    lista.forEach(h => {
      const nombreHotel = hotelMap[h.hotel_id] || '—';
      const tr = document.createElement('tr');
      tr.classList.add('hover:bg-gray-50');
      tr.innerHTML = `
        <td class="px-6 py-3">${h.id}</td>
        <td class="px-6 py-3">${nombreHotel}</td>
        <td class="px-6 py-3">${h.nombre}</td>
        <td class="px-6 py-3">${h.precio}</td>
        <td class="px-6 py-3 text-center space-x-2">
          <button data-id="${h.id}" class="edit bg-yellow-400 text-white px-2 py-1 rounded">Editar</button>
          <button data-id="${h.id}" class="delete bg-red-500 text-white px-2 py-1 rounded">Borrar</button>
        </td>`;
      tbody.appendChild(tr);

      tr.querySelector('.edit').onclick = () => {
        editingId = h.id;
        selHotel.value = h.hotel_id;
        inpNom.value   = h.nombre;
        inpPre.value   = h.precio;
        document.getElementById('form-title').textContent = 'Editar Habitación';
        formCont.classList.remove('hidden');
      };
      tr.querySelector('.delete').onclick = async () => {
        if (!confirm('Borrar esta habitación?')) return;
        const resp = await API.call('habitacion-delete', { id: h.id });
        if (resp.ok) loadHabitaciones();
        else alert('Error al borrar');
      };
    });
  }
});
