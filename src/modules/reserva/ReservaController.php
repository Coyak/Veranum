<?php
// src/modules/reserva/ReservaController.php
namespace Modules\Reserva;
require_once __DIR__ . '/Reserva.php';

class ReservaController {
  private $m;
  public function __construct() {
    $this->m = new Reserva();
  }

  /** GET ?api=reservas */
  public function index() {
    echo json_encode($this->m->all());
  }

  /** POST ?api=reserva-create */
  public function store() {
    $in = json_decode(file_get_contents('php://input'), true) ?: [];
    $ok = $this->m->create($in);
    echo json_encode(['ok'=>$ok]);
  }

  /** POST ?api=reserva-update */
  public function update() {
    $in = json_decode(file_get_contents('php://input'), true) ?: [];
    $ok = $this->m->update($in);
    echo json_encode(['ok'=>$ok]);
  }

  /** POST ?api=reserva-delete */
  public function delete() {
    $in = json_decode(file_get_contents('php://input'), true) ?: [];
    $ok = $this->m->delete((int)($in['id'] ?? 0));
    echo json_encode(['ok'=>$ok]);
  }

  /** POST ?api=reserva-checkin */
  public function checkin() {
    $in = json_decode(file_get_contents('php://input'), true) ?: [];
    $ok = $this->m->setStatus((int)($in['id'] ?? 0), 'ocupada');
    echo json_encode(['ok'=>$ok]);
  }

  /** POST ?api=reserva-servicio */
  public function servicio() {
    $in = json_decode(file_get_contents('php://input'), true) ?: [];
    $ok = $this->m->addService((int)($in['id'] ?? 0), trim($in['servicio'] ?? ''));
    echo json_encode(['ok'=>$ok]);
  }
}
