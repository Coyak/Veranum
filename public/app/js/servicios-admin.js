document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('service-cards-container');
    const addBtn = document.getElementById('add-service-type-btn');

    const formatPrice = (price) => {
        return new Intl.NumberFormat('es-CL', { style: 'currency', currency: 'CLP', minimumFractionDigits: 0 }).format(price);
    };

    const createServiceCard = (service = null) => {
        const isNew = !service;
        const card = document.createElement('div');
        card.className = 'bg-white rounded-lg shadow-md p-5 border border-gray-200';
        card.dataset.id = service ? service.id : 'new';

        const viewModeHTML = `
            <div class="view-mode">
                <h3 class="text-xl font-bold text-gray-800 truncate">${service?.nombre}</h3>
                <p class="text-lg text-gray-600 mt-2">${service ? formatPrice(service.precio) : ''}</p>
                <div class="mt-4 flex justify-end space-x-3">
                    <button class="edit-btn text-sm font-semibold text-blue-600 hover:text-blue-800">Editar</button>
                    <button class="delete-btn text-sm font-semibold text-red-500 hover:text-red-700">Eliminar</button>
                </div>
            </div>
        `;

        const editModeHTML = `
            <div class="edit-mode" ${isNew ? '' : 'style="display:none;"'}>
                <input type="text" class="name-input w-full p-2 border border-gray-300 rounded-md mb-3" value="${service?.nombre || ''}" placeholder="Nombre del Servicio">
                <input type="number" class="price-input w-full p-2 border border-gray-300 rounded-md" value="${service?.precio || ''}" placeholder="Precio (CLP)">
                <div class="mt-4 flex justify-end space-x-3">
                    <button class="save-btn text-sm font-semibold bg-green-500 text-white py-2 px-4 rounded-lg hover:bg-green-600">Guardar</button>
                    <button class="cancel-btn text-sm font-semibold text-gray-600 hover:text-gray-800">Cancelar</button>
                </div>
            </div>
        `;

        card.innerHTML = isNew ? editModeHTML : viewModeHTML + editModeHTML;

        // --- Event Listeners ---
        const switchToEditMode = () => {
            card.querySelector('.view-mode').style.display = 'none';
            card.querySelector('.edit-mode').style.display = 'block';
        };

        const switchToViewMode = () => {
            if (isNew) return card.remove();
            card.querySelector('.view-mode').style.display = 'block';
            card.querySelector('.edit-mode').style.display = 'none';
        };

        const saveHandler = async () => {
            const id = card.dataset.id;
            const payload = {
                nombre: card.querySelector('.name-input').value,
                precio: parseFloat(card.querySelector('.price-input').value)
            };
            if (!payload.nombre || isNaN(payload.precio)) return alert('Nombre y precio son requeridos.');

            const endpoint = id === 'new' ? 'tipos-servicio-create' : 'tipos-servicio-update';
            if (id !== 'new') payload.id = id;

            try {
                const result = await API.call(endpoint, payload);
                if (result.ok) {
                    alert('Servicio guardado.');
                    loadServiceTypes();
                } else {
                    alert('Error al guardar: ' + (result.error || ''));
                }
            } catch (error) {
                alert('Error de red.');
            }
        };

        const deleteHandler = async () => {
            if (!confirm('¿Seguro que quieres eliminar este servicio?')) return;
            try {
                const result = await API.call('tipos-servicio-delete', { id: card.dataset.id });
                if (result.ok) {
                    alert('Servicio eliminado.');
                    card.remove();
                } else {
                    alert('Error al eliminar: ' + (result.error || ''));
                }
            } catch (error) {
                alert('Error de red.');
            }
        };

        if (card.querySelector('.edit-btn')) card.querySelector('.edit-btn').onclick = switchToEditMode;
        if (card.querySelector('.delete-btn')) card.querySelector('.delete-btn').onclick = deleteHandler;
        card.querySelector('.save-btn').onclick = saveHandler;
        card.querySelector('.cancel-btn').onclick = switchToViewMode;

        return card;
    };

    const loadServiceTypes = async () => {
        try {
            const serviceTypes = await API.call('tipos-servicio');
            container.innerHTML = '';
            if (serviceTypes.length > 0) {
                serviceTypes.forEach(st => container.appendChild(createServiceCard(st)));
            } else {
                container.innerHTML = '<p class="text-center text-gray-500 col-span-full">No hay servicios registrados. Haz clic en "Añadir Servicio" para empezar.</p>';
            }
        } catch (error) {
            container.innerHTML = `<p class="text-center text-red-500 col-span-full">Error al cargar servicios: ${error.error || 'Problema de conexión'}</p>`;
        }
    };

    addBtn.onclick = () => {
        const newCard = createServiceCard();
        container.prepend(newCard);
    };

    loadServiceTypes();
}); 