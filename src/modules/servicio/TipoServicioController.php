<?php
// src/modules/servicio/TipoServicioController.php
namespace Modules\Servicio;

require_once __DIR__ . '/TipoServicio.php';

class TipoServicioController {
    private $model;

    public function __construct() {
        $this->model = new TipoServicio();
    }

    private function isAdmin() {
        // Asumimos que el rol se guarda en la sesión al hacer login
        return isset($_SESSION['cliente_role']) && $_SESSION['cliente_role'] === 'admin';
    }

    private function isAllowedToView() {
        if (!isset($_SESSION['cliente_role'])) return false;
        $role = $_SESSION['cliente_role'];
        return $role === 'admin' || $role === 'recepcionista';
    }

    private function denyAccess($message = 'Acceso denegado. Se requiere rol de administrador.') {
        http_response_code(403);
        echo json_encode(['ok' => false, 'error' => $message]);
        exit;
    }

    public function index() {
        if (!$this->isAllowedToView()) {
            $this->denyAccess('Acceso denegado. No tienes permisos para ver esta información.');
        }
        echo json_encode($this->model->all());
    }

    public function store() {
        if (!$this->isAdmin()) $this->denyAccess();
        $in = json_decode(file_get_contents('php://input'), true);
        $ok = $this->model->create($in);
        echo json_encode(['ok' => $ok]);
    }

    public function update() {
        if (!$this->isAdmin()) $this->denyAccess();
        $in = json_decode(file_get_contents('php://input'), true);
        $ok = $this->model->update($in);
        echo json_encode(['ok' => $ok]);
    }

    public function delete() {
        if (!$this->isAdmin()) $this->denyAccess();
        $in = json_decode(file_get_contents('php://input'), true);
        $ok = $this->model->delete((int)($in['id'] ?? 0));
        echo json_encode(['ok' => $ok]);
    }
} 