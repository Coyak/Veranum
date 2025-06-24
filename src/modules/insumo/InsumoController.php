<?php
// src/modules/insumo/InsumoController.php
namespace Modules\Insumo;
require_once __DIR__ . '/Insumo.php';

class InsumoController {
    private $model;
    public function __construct() {
        $this->model = new Insumo();
    }

    private function isCocinero() {
        return isset($_SESSION['cliente_role']) && $_SESSION['cliente_role'] === 'cocinero';
    }
    private function denyAccess($msg = 'Acceso denegado. Solo cocineros.') {
        http_response_code(403);
        echo json_encode(['ok'=>false,'error'=>$msg]);
        exit;
    }

    public function index() {
        if (!$this->isCocinero()) $this->denyAccess();
        echo json_encode($this->model->all());
    }
    public function store() {
        if (!$this->isCocinero()) $this->denyAccess();
        $in = json_decode(file_get_contents('php://input'), true);
        $ok = $this->model->create($in);
        echo json_encode(['ok'=>$ok]);
    }
    public function update() {
        if (!$this->isCocinero()) $this->denyAccess();
        $in = json_decode(file_get_contents('php://input'), true);
        $ok = $this->model->update($in);
        echo json_encode(['ok'=>$ok]);
    }
    public function delete() {
        if (!$this->isCocinero()) $this->denyAccess();
        $in = json_decode(file_get_contents('php://input'), true);
        $ok = $this->model->delete((int)($in['id'] ?? 0));
        echo json_encode(['ok'=>$ok]);
    }
    public function movimiento() {
        if (!$this->isCocinero()) $this->denyAccess();
        $in = json_decode(file_get_contents('php://input'), true);
        $ok = $this->model->registrarMovimiento($in);
        echo json_encode(['ok'=>$ok]);
    }
    public function movimientos() {
        if (!$this->isCocinero()) $this->denyAccess();
        $id_insumo = isset($_GET['id_insumo']) ? (int)$_GET['id_insumo'] : null;
        echo json_encode($this->model->movimientos($id_insumo));
    }
} 