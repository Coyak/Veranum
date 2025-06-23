// public/app/js/rooms.js
document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('search-form');
  const resultsContainer = document.getElementById('results');

  // Establecer fechas por defecto: hoy y mañana
  const today = new Date();
  const tomorrow = new Date();
  tomorrow.setDate(today.getDate() + 1);
  form.fi.valueAsDate = today;
  form.ff.valueAsDate = tomorrow;

  const searchRooms = async () => {
    const fi = form.fi.value;
    const ff = form.ff.value;

    if (!fi || !ff) {
      alert('Debes ingresar ambas fechas.');
      return;
    }

    try {
      const availableRooms = await API.call('habitacion-disponibles', { fi, ff });

      resultsContainer.innerHTML = '';
      if (!availableRooms || availableRooms.length === 0) {
        resultsContainer.innerHTML = `<p class="text-center text-gray-600">No hay habitaciones disponibles en ese rango.</p>`;
        return;
      }

      renderRoomCards(availableRooms, fi, ff);
    } catch (error) {
      console.error('Error al buscar habitaciones:', error);
      resultsContainer.innerHTML = `<p class="text-center text-red-500">Error al cargar las habitaciones. Intenta de nuevo.</p>`;
    }
  };

  const renderRoomCards = (rooms, fi, ff) => {
    rooms.forEach(room => {
      const card = document.createElement('div');
      card.className = 'bg-white rounded shadow p-4 grid grid-cols-1 md:grid-cols-3 gap-4 items-center';
      card.innerHTML = `
        <div>
          <h3 class="font-semibold">#${room.id} — ${room.nombre_habitacion}</h3>
          <p>Hotel: ${room.nombre_hotel}</p>
          <p>Precio: $${parseInt(room.precio, 10)} / día</p>
        </div>
        <div>
          <img src="${room.foto || '/app/assets/img/habitaciones/default.jpg'}" alt="Foto de ${room.nombre_habitacion}" class="w-full h-24 object-cover rounded">
        </div>
        <div class="text-right">
          <button data-id="${room.id}" class="reserve bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">
            Reservar
          </button>
        </div>
      `;
      resultsContainer.appendChild(card);
    });

    // Asignar evento de reserva a los botones
    document.querySelectorAll('.reserve').forEach(btn => {
      btn.onclick = () => handleReservation(btn.dataset.id, fi, ff);
    });
  };

  const handleReservation = async (roomId, fi, ff) => {
    const userId = sessionStorage.getItem('user_id');
    if (!userId) {
      alert('Error de sesión. Por favor, inicia sesión de nuevo.');
      return;
    }
    if (!confirm(`¿Confirmas la reserva de la habitación #${roomId}?`)) {
      return;
    }

    const payload = {
      cliente_id: parseInt(userId, 10),
      habitacion_id: parseInt(roomId, 10),
      fecha_inicio: fi,
      fecha_fin: ff
    };

    try {
      const result = await API.call('reserva-create', payload);
      if (result.ok) {
        alert('¡Reserva confirmada! 🎉');
        searchRooms(); // Refrescar la lista de habitaciones
      } else {
        alert(result.error || 'No se pudo confirmar la reserva.');
      }
    } catch (error) {
      console.error('Error al crear la reserva:', error);
      alert('Error de red al intentar realizar la reserva.');
    }
  };

  form.onsubmit = e => {
    e.preventDefault();
    searchRooms();
  };

  // Cargar habitaciones al iniciar la página
  searchRooms();
});
