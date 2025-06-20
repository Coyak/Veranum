<?php
require_once __DIR__ . '/../models/Hotel.php';

class HotelController {
    private $model;
    public function __construct() {
        $this->model = new Hotel();
    }

    // Listado de hoteles
    public function index() {
        $lista = $this->model->all();
        include __DIR__ . '/../views/hotel/index.php';
    }

    // Formulario crear
    public function create() {
        include __DIR__ . '/../views/hotel/create.php';
    }

    // Almacenar nuevo hotel
    public function store() {
        $this->model->create($_POST['nombre']);
        header('Location: ?ruta=hoteles');
        exit;
    }

    // Formulario editar
    public function edit() {
        $id   = $_GET['id'];
        $item = $this->model->find($id);
        include __DIR__ . '/../views/hotel/edit.php';
    }

    // Guardar edición
    public function update() {
        $this->model->update($_POST['id'], $_POST['nombre']);
        header('Location: ?ruta=hoteles');
        exit;
    }

    // Borrar hotel
    public function delete() {
        $this->model->delete($_GET['id']);
        header('Location: ?ruta=hoteles');
        exit;
    }
}
