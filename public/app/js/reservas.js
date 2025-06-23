document.addEventListener('DOMContentLoaded', async () => {
    const reservationsList = document.getElementById('reservations-list');

    const getStatusChip = (status) => {
        const baseClasses = 'px-3 py-1 text-sm font-semibold rounded-full';
        switch (status) {
            case 'pendiente':
                return `<span class="${baseClasses} bg-yellow-200 text-yellow-800">Pendiente</span>`;
            case 'checkin':
                return `<span class="${baseClasses} bg-green-200 text-green-800">Check-in Realizado</span>`;
            case 'checkout':
                return `<span class="${baseClasses} bg-blue-200 text-blue-800">Finalizada</span>`;
            default:
                return `<span class="${baseClasses} bg-gray-200 text-gray-800">${status}</span>`;
        }
    };

    try {
        const myReservations = await API.call('mis-reservas');
        console.log('Reservas recibidas de la API:', myReservations);

        if (!myReservations || myReservations.length === 0) {
            reservationsList.innerHTML = `<p class="text-center text-gray-600">Aún no has realizado ninguna reserva.</p>`;
            return;
        }

        myReservations.forEach(res => {
            const card = document.createElement('div');
            card.className = 'bg-white rounded shadow p-4 flex items-center justify-between';
            
            const fechaInicio = new Date(res.fecha_inicio + 'T00:00:00').toLocaleDateString('es-ES', { day: '2-digit', month: 'long', year: 'numeric' });
            const fechaFin = new Date(res.fecha_fin + 'T00:00:00').toLocaleDateString('es-ES', { day: '2-digit', month: 'long', year: 'numeric' });

            card.innerHTML = `
                <div class="flex items-center gap-4">
                    <img src="${res.foto || '/app/assets/img/habitaciones/default.jpg'}" alt="Foto de ${res.nombre_habitacion}" class="w-32 h-20 object-cover rounded">
                    <div>
                        <h3 class="text-lg font-bold">${res.nombre_habitacion}</h3>
                        <p class="text-sm text-gray-600">${res.nombre_hotel}</p>
                        <p class="text-sm text-gray-500">${fechaInicio} – ${fechaFin}</p>
                    </div>
                </div>
                <div>
                    ${getStatusChip(res.status)}
                </div>
            `;
            reservationsList.appendChild(card);
        });

    } catch (error) {
        console.error('Error al cargar mis reservas:', error);
        reservationsList.innerHTML = `<p class="text-center text-red-500">No se pudieron cargar tus reservas. Intenta de nuevo más tarde.</p>`;
    }
}); 