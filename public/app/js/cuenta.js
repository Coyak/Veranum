document.addEventListener('DOMContentLoaded', () => {
    // --- Elementos del DOM ---
    const guestNameEl = document.getElementById('guest-name');
    const roomDetailsEl = document.getElementById('room-details');
    const roomCostEl = document.getElementById('room-cost');
    const servicesListEl = document.getElementById('services-list');
    const totalAmountEl = document.getElementById('total-amount');
    const addServicesBtn = document.getElementById('add-services-btn');

    // --- Estado ---
    let reservaId = null;
    let reservaActual = null;

    const formatPrice = (price) => new Intl.NumberFormat('es-CL', { style: 'currency', currency: 'CLP', minimumFractionDigits: 0 }).format(price);

    // --- Lógica de Renderizado ---
    const renderAccount = (reserva, servicios) => {
        reservaActual = reserva;
        const roomPrice = parseFloat(reserva.precio_habitacion);

        guestNameEl.textContent = reserva.cliente_nombre;
        roomDetailsEl.textContent = `Habitación: ${reserva.nombre_habitacion}`;
        roomCostEl.textContent = formatPrice(roomPrice);

        servicesListEl.innerHTML = '';
        let servicesTotal = 0;

        if (servicios && servicios.length > 0) {
            servicios.forEach(s => {
                const servicePrice = parseFloat(s.precio_unitario) * s.cantidad;
                servicesTotal += servicePrice;
                const serviceEl = document.createElement('div');
                serviceEl.className = 'flex justify-between items-center py-1';
                serviceEl.innerHTML = `
                    <div>
                        <p>${s.nombre} (x${s.cantidad})</p>
                        <p class="text-xs text-gray-500">Precio unitario: ${formatPrice(s.precio_unitario)}</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="font-semibold">${formatPrice(servicePrice)}</span>
                        <button data-id="${s.id}" class="remove-service-btn text-red-500 hover:text-red-700 font-bold">Quitar</button>
                    </div>
                `;
                servicesListEl.appendChild(serviceEl);
            });
        } else {
            servicesListEl.innerHTML = '<p class="text-gray-500">No hay servicios adicionales.</p>';
        }

        totalAmountEl.textContent = formatPrice(roomPrice + servicesTotal);
    };

    // --- Lógica de Datos y Eventos ---
    const loadAccountData = async () => {
        const params = new URLSearchParams(window.location.search);
        reservaId = params.get('id');
        if (!reservaId) {
            alert('No se ha especificado una reserva.');
            window.location.href = '/app/pages/recepcion.html';
            return;
        }

        try {
            // Se necesitan dos llamadas: una para los detalles de la reserva y otra para los servicios
            const [reservaData, serviciosData] = await Promise.all([
                API.call('reserva-details', { id: reservaId }, 'GET'),
                API.call('servicios-reserva', { reserva_id: reservaId }, 'GET')
            ]);
            
            if (reservaData && !reservaData.error) {
                renderAccount(reservaData, serviciosData.servicios);
            } else {
                throw new Error(reservaData.error || 'No se pudo cargar la información de la reserva.');
            }
        } catch (error) {
            console.error('Error al cargar la cuenta:', error);
            alert(`Error: ${error.message}`);
            guestNameEl.textContent = 'Error al cargar';
        }
    };

    const handleRemoveService = async (servicioId) => {
        if (!confirm('¿Estás seguro de que quieres quitar este servicio de la cuenta?')) return;

        try {
            const result = await API.call('servicio-delete', { id: servicioId }, 'POST');
            if (result.ok) {
                // Recargar los datos para mostrar el estado actualizado
                loadAccountData();
            } else {
                throw new Error(result.error || 'No se pudo eliminar el servicio.');
            }
        } catch (error) {
            console.error('Error al eliminar servicio:', error);
            alert(`Error: ${error.message}`);
        }
    };

    // --- Asignación de Eventos ---
    addServicesBtn.addEventListener('click', () => {
        window.location.href = `/app/pages/asignar-servicios.html?reserva_id=${reservaId}`;
    });

    servicesListEl.addEventListener('click', (e) => {
        if (e.target.classList.contains('remove-service-btn')) {
            const servicioId = e.target.dataset.id;
            handleRemoveService(servicioId);
        }
    });

    // --- Inicialización ---
    loadAccountData();
}); 