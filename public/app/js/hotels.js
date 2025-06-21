// public/app/js/hotels.js

document.addEventListener('DOMContentLoaded', () => {
  // Carga inicial de hoteles
  loadHotels();

  // Evento para crear un nuevo hotel
  document.getElementById('btn-new').onclick = async () => {
    const nombre = prompt('Ingrese el nombre del nuevo hotel:');
    if (!nombre) return;
    const resp = await API.call('hotel-create', { nombre });
    if (resp.ok) {
      loadHotels();
    } else {
      alert('Error al crear hotel: ' + (resp.error || 'Desconocido'));
    }
  };
});



async function loadHotels() {
  const lista = await API.call('hoteles');
  const tbody = document.getElementById('hotel-tbody');
  tbody.innerHTML = '';  // limpia la tabla

  lista.forEach(h => {
    // 1) Creo la fila
    const tr = document.createElement('tr');
    tr.classList.add('border-b');

    // 2) Celdas ID y Nombre
    const tdId     = document.createElement('td');
    tdId.classList.add('p-2');
    tdId.textContent = h.id;

    const tdNombre = document.createElement('td');
    tdNombre.classList.add('p-2');
    tdNombre.textContent = h.nombre;

    // 3) Celda Acciones con dos botones
    const tdAcc = document.createElement('td');
    tdAcc.classList.add('p-2', 'text-center', 'space-x-2');

    // — Botón Editar —
    const btnEdit = document.createElement('button');
    btnEdit.textContent = 'Editar';
    btnEdit.dataset.id = h.id;
    btnEdit.classList.add('bg-yellow-400','text-white','px-2','py-1','rounded','hover:bg-yellow-500');
    tdAcc.appendChild(btnEdit);

    // — Botón Borrar —
    const btnDel = document.createElement('button');
    btnDel.textContent = 'Borrar';
    btnDel.dataset.id = h.id;
    btnDel.classList.add('bg-red-500','text-white','px-2','py-1','rounded','hover:bg-red-600');
    tdAcc.appendChild(btnDel);

    // 4) Ensamblo la fila
    tr.appendChild(tdId);
    tr.appendChild(tdNombre);
    tr.appendChild(tdAcc);
    tbody.appendChild(tr);

    // 5) Atacho los eventos SOBRE LA MARCHA

    // Editar
    btnEdit.onclick = async () => {
      const nuevo = prompt('Nuevo nombre del hotel:', h.nombre);
      if (!nuevo || nuevo.trim() === '' || nuevo.trim() === h.nombre) return;
      const resp = await API.call('hotel-update', { id: h.id, nombre: nuevo.trim() });
      if (resp.ok) loadHotels();
      else alert('Error al editar: ' + (resp.error || 'Desconocido'));
    };

    // Borrar
    btnDel.onclick = async () => {
      if (!confirm('¿Borrar este hotel?')) return;
      const resp = await API.call('hotel-delete', { id: h.id });
      if (resp.ok) loadHotels();
      else alert('Error al borrar: ' + (resp.error || 'Desconocido'));
    };
  });
}

