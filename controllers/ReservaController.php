<?php
require_once __DIR__ . '/../models/Reserva.php';
require_once __DIR__ . '/../models/Habitacion.php';

class ReservaController {
  private $model;
  private $habitacionModel;

  public function __construct() {
    $this->model           = new Reserva();
    $this->habitacionModel = new Habitacion();
  }

  public function index() {
    $clienteId = $_SESSION['cliente_id'];
    $lista     = $this->model->allByCliente($clienteId);
    include __DIR__ . '/../views/reserva/index.php';
  }

  /**
   * 1) Muestra el formulario donde el usuario ingresa fechas
   *    para buscar disponibilidad.
   */
  public function search() {
    include __DIR__ . '/../views/reserva/search.php';
  }

  /**
   * 2) Recibe las fechas por GET, obtiene sólo las habitaciones
   *    libres en ese rango y muestra el formulario de reserva.
   */
  public function create() {
    // 2.1 Recoger fechas del query string
    $fecha_inicio = $_GET['fecha_inicio'] ?? null;
    $fecha_fin    = $_GET['fecha_fin']    ?? null;

    // 2.2 Si faltan fechas, volvemos a la búsqueda
    if (!$fecha_inicio || !$fecha_fin) {
      header('Location: ?ruta=reserva-buscar');
      exit;
    }

    // 2.3 Obtener habitaciones disponibles
    $habitaciones = $this->model->availableRooms($fecha_inicio, $fecha_fin);

    // 2.4 Incluir la vista que mostrará el <select> con $habitaciones
    include __DIR__ . '/../views/reserva/create.php';
  }

  /**
   * 3) Procesa el POST del formulario de reserva:
   *    - Vuelve a chequear disponibilidad
   *    - Inserta la reserva si sigue libre
   *    - Redirige al listado
   */
  public function store() {
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_fin    = $_POST['fecha_fin'];
    $habId        = $_POST['habitacion_id'];

    // 3.1 Validar de nuevo que sigue libre
    $disponibles = array_column(
      $this->model->availableRooms($fecha_inicio, $fecha_fin),
      'id'
    );
    if (!in_array($habId, $disponibles)) {
      die("<p class='p-4 bg-red-100 text-red-700'>
             La habitación ya no está disponible.
           </p>");
    }

    // 3.2 Completar datos y guardar
    $_POST['cliente_id'] = $_SESSION['cliente_id'];
    $this->model->create($_POST);

    // 3.3 Redirigir al listado de reservas
    header('Location: ?ruta=reservas');
    exit;
  }

  // … aquí irían index(), edit(), update(), delete() …
}

