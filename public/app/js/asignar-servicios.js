document.addEventListener('DOMContentLoaded', () => {
    // --- Elementos del DOM ---
    const availableServicesContainer = document.getElementById('available-services-container');
    const summary = {
        guestName: document.getElementById('summary-guest-name'),
        roomName: document.getElementById('summary-room-name'),
        servicesList: document.getElementById('summary-services-list'),
        totalPrice: document.getElementById('summary-total-price')
    };
    const backBtn = document.getElementById('back-to-list-btn');
    const saveBtn = document.getElementById('save-services-btn');
    
    // --- Estado de la Aplicación ---
    let currentReservation = null;
    let availableServices = [];
    let selectedServices = [];

    const formatPrice = (price) => new Intl.NumberFormat('es-CL', { style: 'currency', currency: 'CLP', minimumFractionDigits: 0 }).format(price);

    // --- LÓGICA DE RENDERIZADO ---
    const renderAvailableServices = () => {
        availableServicesContainer.innerHTML = '';
        availableServices.forEach(service => {
            const div = document.createElement('div');
            div.className = 'flex items-center justify-between p-3 bg-gray-50 rounded-lg';
            div.innerHTML = `
                <div>
                    <label for="service-${service.id}" class="font-medium text-gray-700">${service.nombre}</label>
                    <p class="text-sm text-gray-500">${formatPrice(service.precio)}</p>
                </div>
                <div class="flex items-center">
                    <input type="checkbox" id="service-${service.id}" data-id="${service.id}" data-precio="${service.precio}" class="service-checkbox h-5 w-5">
                    <input type="number" min="1" value="1" class="quantity-input ml-2 w-16 p-1 border rounded hidden">
                </div>
            `;
            availableServicesContainer.appendChild(div);
        });
    };
    
    const updateSummary = () => {
        summary.guestName.textContent = currentReservation.cliente_nombre;
        summary.roomName.textContent = `Habitación: ${currentReservation.nombre_habitacion}`;
        summary.servicesList.innerHTML = '';
        let servicesTotal = 0;
        
        selectedServices.forEach(s => {
            const serviceInfo = availableServices.find(as => as.id === s.id);
            const itemDiv = document.createElement('div');
            itemDiv.className = 'flex items-center justify-between text-sm py-1';
            itemDiv.innerHTML = `
                <span class="flex-grow">${serviceInfo.nombre} (x${s.cantidad})</span>
                <span class="mr-4">${formatPrice(s.precio * s.cantidad)}</span>
                <button data-id="${s.id}" class="remove-service-btn text-red-500 hover:text-red-700 font-bold">X</button>
            `;
            summary.servicesList.appendChild(itemDiv);
            servicesTotal += s.precio * s.cantidad;
        });

        if (selectedServices.length === 0) {
            summary.servicesList.innerHTML = '<p class="text-sm text-gray-400">Ningún servicio seleccionado.</p>';
        }
        summary.totalPrice.textContent = formatPrice(servicesTotal);
    };

    const removeService = (serviceId) => {
        const checkbox = availableServicesContainer.querySelector(`#service-${serviceId}`);
        if (checkbox) {
            checkbox.checked = false;
            checkbox.nextElementSibling.classList.add('hidden');
        }
        selectedServices = selectedServices.filter(s => s.id !== serviceId);
        updateSummary();
    };

    // --- LÓGICA DE EVENTOS ---
    summary.servicesList.addEventListener('click', e => {
        if (e.target.classList.contains('remove-service-btn')) {
            removeService(parseInt(e.target.dataset.id));
        }
    });

    availableServicesContainer.addEventListener('change', e => {
        if (e.target.classList.contains('service-checkbox')) {
            const id = parseInt(e.target.dataset.id);
            const quantityInput = e.target.nextElementSibling;
            if (e.target.checked) {
                quantityInput.classList.remove('hidden');
                selectedServices.push({ id, cantidad: 1, precio: parseFloat(e.target.dataset.precio) });
            } else {
                quantityInput.classList.add('hidden');
                selectedServices = selectedServices.filter(s => s.id !== id);
            }
            updateSummary();
        }
    });

    availableServicesContainer.addEventListener('input', e => {
        if (e.target.classList.contains('quantity-input')) {
            const id = parseInt(e.target.previousElementSibling.dataset.id);
            const service = selectedServices.find(s => s.id === id);
            if (service) {
                service.cantidad = parseInt(e.target.value) || 1;
                updateSummary();
            }
        }
    });

    saveBtn.addEventListener('click', async () => {
        if (selectedServices.length === 0) {
            return alert('No has seleccionado ningún servicio nuevo para añadir.');
        }
        if (!confirm('¿Confirmas añadir estos servicios a la cuenta del huésped?')) return;
        const payload = {
            reserva_id: currentReservation.id,
            servicios: selectedServices.map(s => ({ id: s.id, cantidad: s.cantidad, precio: s.precio }))
        };
        try {
            const result = await API.call('asignar-servicios', payload, 'POST');
            if (result.ok) {
                alert('Servicios añadidos con éxito.');
                window.location.href = `/app/pages/cuenta.html?id=${currentReservation.id}`;
            } else {
                alert('Error al guardar los servicios: ' + (result.error || ''));
            }
        } catch (error) {
            alert('Error de red al guardar.');
        }
    });

    backBtn.addEventListener('click', () => {
        if (currentReservation) {
            window.location.href = `/app/pages/cuenta.html?id=${currentReservation.id}`;
        } else {
            window.location.href = '/app/pages/recepcion.html';
        }
    });

    // --- INICIALIZACIÓN ---
    const init = async () => {
        const params = new URLSearchParams(window.location.search);
        const reservaId = params.get('reserva_id');
        if (!reservaId) {
            alert('ID de reserva no encontrado. Volviendo a la recepción.');
            window.location.href = '/app/pages/recepcion.html';
            return;
        }
        
        try {
            const [reservaData, servicesData] = await Promise.all([
                API.call('reserva-details', { id: reservaId }, 'GET'),
                API.call('tipos-servicio')
            ]);
            
            if (reservaData.error) throw new Error(reservaData.error);
            if (!Array.isArray(servicesData)) throw new Error('La respuesta de servicios no es válida.');

            currentReservation = reservaData;
            availableServices = servicesData;

            renderAvailableServices();
            updateSummary();
        } catch (error) {
            console.error("Error al cargar datos:", error);
            alert("No se pudieron cargar los datos necesarios: " + error.message);
        }
    };
    
    init();
}); 