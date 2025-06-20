<?php
require_once __DIR__ . '/../models/Habitacion.php';
require_once __DIR__ . '/../models/Hotel.php';

class HabitacionController {
    private $model;
    private $hotelModel;

    public function __construct() {
        $this->model = new Habitacion();
        $this->hotelModel = new Hotel();
    }

    public function index() {
        $lista = $this->model->all();
        include __DIR__ . '/../views/habitacion/index.php';
    }

    public function create() {
        $hoteles = $this->hotelModel->all();
        include __DIR__ . '/../views/habitacion/create.php';
    }

    public function store() {
        $this->model->create($_POST);
        header('Location: ?ruta=habitaciones');
        exit;
    }

    public function edit() {
        $id       = $_GET['id'];
        $item     = $this->model->find($id);
        $hoteles  = $this->hotelModel->all();
        include __DIR__ . '/../views/habitacion/edit.php';
    }

    public function update() {
        $this->model->update($_POST['id'], $_POST);
        header('Location: ?ruta=habitaciones');
        exit;
    }

    public function delete() {
        $this->model->delete($_GET['id']);
        header('Location: ?ruta=habitaciones');
        exit;
    }
}
