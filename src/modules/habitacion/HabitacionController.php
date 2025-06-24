<?php
namespace Modules\Habitacion;
require_once __DIR__ . '/Habitacion.php';

class HabitacionController {
  private $m;
  public function __construct() {
    $this->m = new Habitacion();
  }

  // GET /api.php?api=habitaciones&hotel_id=#
  public function index() {
    $hid = (int)($_GET['hotel_id'] ?? 0);
    if ($hid > 0) {
        $data = $this->m->allByHotel($hid);
    } else {
        $data = $this->m->all();         // <— aquí
    }
    echo json_encode($data);
  }

  // POST /api.php?api=habitacion-create
  public function store() {
    $in = json_decode(file_get_contents('php://input'), true);
    echo json_encode(['ok' => $this->m->create($in)]);
  }

  // POST /api.php?api=habitacion-update
  public function update() {
    $in = json_decode(file_get_contents('php://input'), true);
    $id = (int)($in['id'] ?? 0);
    echo json_encode(['ok' => $this->m->update($id, $in)]);
  }

  // POST /api.php?api=habitacion-delete
  public function delete() {
    $in = json_decode(file_get_contents('php://input'), true);
    $id = (int)($in['id'] ?? 0);
    echo json_encode(['ok' => $this->m->delete($id)]);
  }
    /** 
   * GET ?api=habitacion-disponibles&fi=YYYY-MM-DD&ff=YYYY-MM-DD
   */
  public function available() {
    $in = json_decode(file_get_contents('php://input'), true);
    if (!$in || !isset($in['fi'], $in['ff'])) {
        http_response_code(400);
        echo json_encode(['ok' => false, 'error' => 'Fechas de inicio y fin son requeridas.']);
        return;
    }

    $disponibles = $this->m->findAvailable($in['fi'], $in['ff']);
    echo json_encode($disponibles);
  }

  public function reporteOcupacion() {
    $data = $this->m->getOcupacion();
    echo json_encode($data);
  }

}
