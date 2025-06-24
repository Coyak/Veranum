document.addEventListener('DOMContentLoaded', () => {
  guard.only(['cocinero']);
  cargarInsumos();

  const form = document.getElementById('form-insumo');
  const btnLimpiar = document.getElementById('btn-limpiar');
  const tabla = document.getElementById('tabla-insumos');

  form.onsubmit = async (e) => {
    e.preventDefault();
    const id = document.getElementById('insumo-id').value;
    const nombre = document.getElementById('nombre').value.trim();
    const unidad = document.getElementById('unidad').value.trim();
    const descripcion = document.getElementById('descripcion').value.trim();
    const stock_actual = document.getElementById('stock_actual').value;
    const data = { nombre, unidad, descripcion, stock_actual };
    let ok = false;
    if (id) {
      data.id = id;
      ok = await API.call('insumo-update', data);
    } else {
      ok = await API.call('insumo-create', data);
    }
    if (ok && ok.ok) {
      alert('Insumo guardado correctamente');
      form.reset();
      document.getElementById('insumo-id').value = '';
      cargarInsumos();
    } else {
      alert('Error al guardar el insumo');
    }
  };

  btnLimpiar.onclick = () => {
    form.reset();
    document.getElementById('insumo-id').value = '';
  };

  async function cargarInsumos() {
    const res = await API.call('insumos');
    if (!res || !Array.isArray(res)) {
      tabla.innerHTML = '<tr><td colspan="4">No se pudieron cargar los insumos</td></tr>';
      return;
    }
    tabla.innerHTML = '';
    res.forEach(insumo => {
      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td class="border px-2 py-1">${insumo.nombre}</td>
        <td class="border px-2 py-1">${insumo.unidad}</td>
        <td class="border px-2 py-1">${insumo.stock_actual}</td>
        <td class="border px-2 py-1 flex flex-col gap-1">
          <button class="btn bg-yellow-400 text-black mb-1" onclick="editarInsumo(${insumo.id})">Editar</button>
          <button class="btn bg-red-500 text-white mb-1" onclick="eliminarInsumo(${insumo.id})">Eliminar</button>
          <button class="btn bg-blue-500 text-white" onclick="registrarConsumo(${insumo.id}, '${insumo.nombre}', '${insumo.unidad}')">Registrar Consumo</button>
        </td>
      `;
      tabla.appendChild(tr);
    });
  }

  window.editarInsumo = async (id) => {
    const res = await API.call('insumos');
    const insumo = res.find(i => i.id == id);
    if (!insumo) return;
    document.getElementById('insumo-id').value = insumo.id;
    document.getElementById('nombre').value = insumo.nombre;
    document.getElementById('unidad').value = insumo.unidad;
    document.getElementById('descripcion').value = insumo.descripcion || '';
    document.getElementById('stock_actual').value = insumo.stock_actual;
  };

  window.eliminarInsumo = async (id) => {
    if (!confirm('¿Seguro que deseas eliminar este insumo?')) return;
    const ok = await API.call('insumo-delete', { id });
    if (ok && ok.ok) {
      alert('Insumo eliminado correctamente');
      cargarInsumos();
    } else {
      alert('Error al eliminar el insumo');
    }
  };

  window.registrarConsumo = (id, nombre, unidad) => {
    const cantidad = prompt(`¿Cuánto ${unidad} de "${nombre}" se consumió?`);
    if (!cantidad || isNaN(cantidad) || cantidad <= 0) return;
    const observacion = prompt('Observación (opcional):') || '';
    API.call('insumo-movimiento', {
      id_insumo: id,
      tipo_movimiento: 'consumo',
      cantidad,
      observacion
    }).then(res => {
      if (res && res.ok) {
        alert('Consumo registrado');
        cargarInsumos();
      } else {
        alert('Error al registrar consumo');
      }
    });
  };
}); 