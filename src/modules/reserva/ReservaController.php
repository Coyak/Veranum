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
    $ok = $this->m->setStatus((int)($in['id'] ?? 0), 'checkin');
    echo json_encode(['ok'=>$ok]);
  }

  /** POST ?api=reserva-servicio */
  public function servicio() {
    $in = json_decode(file_get_contents('php://input'), true) ?: [];
    $ok = $this->m->addService((int)($in['id'] ?? 0), trim($in['servicio'] ?? ''));
    echo json_encode(['ok'=>$ok]);
  }

  public function misReservas() {
    if (!isset($_SESSION['cliente_id'])) {
      http_response_code(403);
      echo json_encode(['ok' => false, 'error' => 'Acceso denegado. Debes iniciar sesión.']);
      return;
    }
    $cliente_id = $_SESSION['cliente_id'];
    $reservas = $this->m->findByClientId($cliente_id);
    echo json_encode($reservas);
  }

  /** GET ?api=reservas-checkin */
  public function getCheckedIn() {
    // Podríamos añadir una validación de rol aquí para asegurar que solo recepcionistas accedan
    $reservas = $this->m->findByStatus('checkin');
    echo json_encode($reservas);
  }

  /** GET ?api=reservas-recepcion */
  public function getForReception() {
    $reservas = $this->m->findForReception();
    echo json_encode($reservas);
  }

  /** GET ?api=reserva-details */
  public function getDetails() {
    $id = $_GET['id'] ?? null;
    if (!$id) {
      http_response_code(400);
      echo json_encode(['ok' => false, 'error' => 'ID de reserva no especificado']);
      return;
    }
    $reserva = $this->m->findById((int)$id);
    if ($reserva) {
      echo json_encode($reserva);
    } else {
      http_response_code(404);
      echo json_encode(['ok' => false, 'error' => 'Reserva no encontrada']);
    }
  }
}
