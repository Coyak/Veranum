// public/app/js/recepcion.js
document.addEventListener('DOMContentLoaded', () => {
    const receptionList = document.getElementById('reception-list');
    const refreshBtn = document.getElementById('refresh-btn');

    const getStatusChip = (status) => {
        const base = 'px-3 py-1 text-sm font-semibold rounded-full';
        switch (status) {
            case 'pendiente': return `<span class="${base} bg-yellow-200 text-yellow-800">Pendiente</span>`;
            case 'checkin': return `<span class="${base} bg-green-200 text-green-800">Check-in</span>`;
            case 'checkout': return `<span class="${base} bg-blue-200 text-blue-800">Check-out</span>`;
            default: return `<span class="${base} bg-gray-200 text-gray-800">${status}</span>`;
        }
    };

    const loadReservations = async () => {
        try {
            const reservations = await API.call('reservas-recepcion');
            receptionList.innerHTML = '';

            if (!reservations || reservations.length === 0) {
                receptionList.innerHTML = `<p class="text-center text-gray-600">No hay ninguna reserva pendiente o con check-in.</p>`;
                return;
            }

            reservations.forEach(res => {
                const card = document.createElement('div');
                card.className = 'bg-white rounded shadow p-4 flex items-center justify-between';
                
                const fechaInicio = new Date(res.fecha_inicio + 'T00:00:00').toLocaleDateString('es-ES');
                const fechaFin = new Date(res.fecha_fin + 'T00:00:00').toLocaleDateString('es-ES');

                let actions = '';
                if (res.status === 'pendiente') {
                    actions = `<button data-id="${res.id}" class="checkin-btn bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">Hacer Check-in</button>`;
                } else if (res.status === 'checkin') {
                    actions = `<a href="/app/pages/cuenta.html?id=${res.id}" class="view-account-btn bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">Ver Cuenta</a>`;
                }
                
                card.innerHTML = `
                    <div class="flex items-center gap-4">
                        <div>
                            <p class="text-sm font-bold text-gray-500">ID Reserva: ${res.id}</p>
                            <h3 class="text-lg font-bold">${res.cliente_nombre}</h3>
                            <p class="text-sm text-gray-600">Habitación: ${res.nombre_habitacion}</p>
                            <p class="text-sm text-gray-500">${fechaInicio} – ${fechaFin}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        ${getStatusChip(res.status)}
                        ${actions}
                    </div>`;
                receptionList.appendChild(card);
            });

            document.querySelectorAll('.checkin-btn').forEach(btn => {
                btn.onclick = () => handleCheckin(btn.dataset.id);
            });

        } catch (error) {
            console.error('Error al cargar reservas:', error);
            receptionList.innerHTML = `<p class="text-center text-red-500">No se pudieron cargar las reservas.</p>`;
        }
    };

    const handleCheckin = async (id) => {
        if (!confirm(`¿Confirmas el check-in para la reserva #${id}?`)) return;
        try {
            const result = await API.call('reserva-checkin', { id });
            if (result.ok) {
                alert('¡Check-in realizado con éxito!');
                loadReservations(); // Recargar la lista
            } else {
                alert(result.error || 'No se pudo realizar el check-in.');
            }
        } catch (error) {
            console.error('Error en el check-in:', error);
            alert('Error de red al intentar hacer el check-in.');
        }
    };

    refreshBtn.onclick = loadReservations;

    loadReservations();
});
