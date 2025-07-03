document.addEventListener('DOMContentLoaded', () => {
  guard.only(['gerente']);
  
  // Configuración de colores del sistema (debe ir antes de cualquier uso)
  const coloresSistema = {
    dorado: '#bfa14a',
    doradoClaro: '#d4b76b',
    doradoOscuro: '#a8903a',
    gris: '#6b7280',
    grisClaro: '#9ca3af',
    grisOscuro: '#4b5563'
  };

  // Inicializar gráficos
  inicializarGraficos();
  
  // Cargar datos
  cargarOcupacion();
  cargarIngresos();
  cargarDatosGraficos();

  function inicializarGraficos() {
    // Gráfico 1: Ocupación por Hotel (Barras)
    const ctxOcupacion = document.getElementById('ocupacionChart').getContext('2d');
    window.ocupacionChart = new Chart(ctxOcupacion, {
      type: 'bar',
      data: {
        labels: ['Cargando...'],
        datasets: [{
          label: 'Habitaciones Ocupadas',
          data: [0],
          backgroundColor: coloresSistema.dorado,
          borderColor: coloresSistema.doradoOscuro,
          borderWidth: 2
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            labels: {
              color: coloresSistema.grisOscuro
            }
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              color: coloresSistema.grisOscuro
            }
          },
          x: {
            ticks: {
              color: coloresSistema.grisOscuro
            }
          }
        }
      }
    });

    // Gráfico 2: Tipos de Habitación (Torta)
    const ctxTipos = document.getElementById('tiposHabitacionChart').getContext('2d');
    window.tiposHabitacionChart = new Chart(ctxTipos, {
      type: 'pie',
      data: {
        labels: ['Cargando...'],
        datasets: [{
          data: [1],
          backgroundColor: [
            coloresSistema.dorado,
            coloresSistema.doradoClaro,
            coloresSistema.gris,
            coloresSistema.grisClaro
          ],
          borderWidth: 2,
          borderColor: '#ffffff'
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            labels: {
              color: coloresSistema.grisOscuro
            }
          }
        }
      }
    });

    // Gráfico 3: Ingresos Mensuales (Líneas)
    const ctxIngresos = document.getElementById('ingresosChart').getContext('2d');
    window.ingresosChart = new Chart(ctxIngresos, {
      type: 'line',
      data: {
        labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'],
        datasets: [{
          label: 'Ingresos ($)',
          data: [0, 0, 0, 0, 0, 0],
          borderColor: coloresSistema.dorado,
          backgroundColor: coloresSistema.doradoClaro + '20',
          borderWidth: 3,
          fill: true,
          tension: 0.4
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            labels: {
              color: coloresSistema.grisOscuro
            }
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              color: coloresSistema.grisOscuro
            }
          },
          x: {
            ticks: {
              color: coloresSistema.grisOscuro
            }
          }
        }
      }
    });

    // Gráfico 4: Servicios Más Solicitados (Dona)
    const ctxServicios = document.getElementById('serviciosChart').getContext('2d');
    window.serviciosChart = new Chart(ctxServicios, {
      type: 'doughnut',
      data: {
        labels: ['Cargando...'],
        datasets: [{
          data: [1],
          backgroundColor: [
            coloresSistema.dorado,
            coloresSistema.doradoClaro,
            coloresSistema.gris,
            coloresSistema.grisClaro,
            coloresSistema.doradoOscuro
          ],
          borderWidth: 3,
          borderColor: '#ffffff'
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            labels: {
              color: coloresSistema.grisOscuro
            }
          }
        }
      }
    });
  }

  async function cargarDatosGraficos() {
    try {
      // Cargar datos para gráfico de ocupación por hotel
      const datosOcupacion = await API.call('reporte-ocupacion-por-hotel');
      if (datosOcupacion && Array.isArray(datosOcupacion)) {
        const labels = datosOcupacion.map(item => item.hotel || 'Hotel ' + item.id);
        const data = datosOcupacion.map(item => item.ocupadas || 0);
        
        window.ocupacionChart.data.labels = labels;
        window.ocupacionChart.data.datasets[0].data = data;
        window.ocupacionChart.update();
      }

      // Cargar datos para gráfico de tipos de habitación
      const datosTipos = await API.call('reporte-tipos-habitacion');
      if (datosTipos && Array.isArray(datosTipos)) {
        const labels = datosTipos.map(item => item.tipo || 'Tipo ' + item.id);
        const data = datosTipos.map(item => item.cantidad || 0);
        
        window.tiposHabitacionChart.data.labels = labels;
        window.tiposHabitacionChart.data.datasets[0].data = data;
        window.tiposHabitacionChart.update();
      }

      // Cargar datos para gráfico de ingresos mensuales
      const datosIngresos = await API.call('reporte-ingresos-mensuales');
      if (datosIngresos && Array.isArray(datosIngresos)) {
        const labels = datosIngresos.map(item => item.mes || 'Mes ' + item.id);
        const data = datosIngresos.map(item => item.ingresos || 0);
        
        window.ingresosChart.data.labels = labels;
        window.ingresosChart.data.datasets[0].data = data;
        window.ingresosChart.update();
      }

      // Cargar datos para gráfico de servicios
      const datosServicios = await API.call('reporte-servicios-populares');
      if (datosServicios && Array.isArray(datosServicios)) {
        const labels = datosServicios.map(item => item.servicio || 'Servicio ' + item.id);
        const data = datosServicios.map(item => item.solicitudes || 0);
        
        window.serviciosChart.data.labels = labels;
        window.serviciosChart.data.datasets[0].data = data;
        window.serviciosChart.update();
      }

    } catch (error) {
      console.error('Error cargando datos de gráficos:', error);
      // Mostrar datos de ejemplo si no hay datos reales
      mostrarDatosEjemplo();
    }
  }

  function mostrarDatosEjemplo() {
    // Datos de ejemplo para demostración
    const datosEjemplo = {
      ocupacion: {
        labels: ['Hotel Central', 'Hotel Playa', 'Hotel Montaña'],
        data: [12, 8, 15]
      },
      tipos: {
        labels: ['Individual', 'Doble', 'Suite', 'Familiar'],
        data: [25, 30, 15, 10]
      },
      ingresos: {
        labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'],
        data: [45000, 52000, 48000, 61000, 58000, 65000]
      },
      servicios: {
        labels: ['WiFi', 'Limpieza', 'Restaurante', 'Spa', 'Transporte'],
        data: [45, 38, 32, 25, 18]
      }
    };

    // Actualizar gráficos con datos de ejemplo
    window.ocupacionChart.data.labels = datosEjemplo.ocupacion.labels;
    window.ocupacionChart.data.datasets[0].data = datosEjemplo.ocupacion.data;
    window.ocupacionChart.update();

    window.tiposHabitacionChart.data.labels = datosEjemplo.tipos.labels;
    window.tiposHabitacionChart.data.datasets[0].data = datosEjemplo.tipos.data;
    window.tiposHabitacionChart.update();

    window.ingresosChart.data.labels = datosEjemplo.ingresos.labels;
    window.ingresosChart.data.datasets[0].data = datosEjemplo.ingresos.data;
    window.ingresosChart.update();

    window.serviciosChart.data.labels = datosEjemplo.servicios.labels;
    window.serviciosChart.data.datasets[0].data = datosEjemplo.servicios.data;
    window.serviciosChart.update();
  }

  async function cargarOcupacion() {
    const res = await API.call('reporte-ocupacion');
    const tabla = document.getElementById('tabla-ocupacion');
    if (!res || !Array.isArray(res)) {
      tabla.innerHTML = '<tr><td colspan="2" class="px-4 py-2 text-center text-gray-500">No se pudo cargar la ocupación</td></tr>';
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
      tabla.innerHTML = '<tr><td colspan="2" class="px-4 py-2 text-center text-gray-500">No se pudo cargar los ingresos</td></tr>';
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