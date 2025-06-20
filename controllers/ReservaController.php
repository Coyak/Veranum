<?php
require_once __DIR__ . '/../models/Reserva.php';
require_once __DIR__ . '/../models/Habitacion.php';

class ReservaController {
  private $model;
  private $habitacionModel;

  public function __construct() {
    $this->model            = new Reserva();
    $this->habitacionModel  = new Habitacion();
  }

  // Listado de reservas del cliente logueado
  public function index() {
    $clienteId = $_SESSION['cliente_id'];
    $lista     = $this->model->allByCliente($clienteId);
    include __DIR__ . '/../views/reserva/index.php';
  }

  // Formulario para reservar
  public function create() {
    $habitaciones = $this->habitacionModel->all();
    include __DIR__ . '/../views/reserva/create.php';
  }

  // Guarda nueva reserva
  public function store() {
    $_POST['cliente_id'] = $_SESSION['cliente_id'];
    $this->model->create($_POST);
    header('Location: ?ruta=reservas');
    exit;
  }

  // Formulario para editar una reserva existente
  public function edit() {
    $item         = $this->model->find($_GET['id']);
    $habitaciones = $this->habitacionModel->all();
    include __DIR__ . '/../views/reserva/edit.php';
  }

  // Actualiza la reserva en BD
  public function update() {
    $this->model->update($_POST['id'], $_POST);
    header('Location: ?ruta=reservas');
    exit;
  }

  // Borra una reserva
  public function delete() {
    $this->model->delete($_GET['id']);
    header('Location: ?ruta=reservas');
    exit;
  }
}
