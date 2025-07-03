<?php
namespace Modules\Hotel;
require_once __DIR__ . '/Hotel.php';

class HotelController {
  private $m;
  public function __construct() {
    $this->m = new Hotel();
  }

  // GET /api.php?api=hoteles
  public function index() {
    echo json_encode($this->m->all());
  }

  // POST /api.php?api=hotel-create
  public function store() {
    $in = json_decode(file_get_contents('php://input'), true);
    echo json_encode(['ok' => $this->m->create($in)]);
  }

  // POST /api.php?api=hotel-update
  public function update() {
    $in = json_decode(file_get_contents('php://input'), true);
    $id = (int)($in['id'] ?? 0);
    echo json_encode([
        'ok' => $this->m->update($id, $in)
    ]);
}

  // GET /api.php?api=hotel-delete&id=123
  public function delete() {
    // 1) Intento leer JSON
    $in = json_decode(file_get_contents('php://input'), true);
    $id = isset($in['id'])
        ? (int)$in['id']
        : (int)($_GET['id'] ?? 0);

    // 2) Borro y devuelvo JSON
    echo json_encode([
        'ok' => $this->m->delete($id)
    ]);
    }

  // GET /api.php?api=reporte-ocupacion-por-hotel
  public function reporteOcupacionPorHotel() {
    echo json_encode($this->m->getOcupacionPorHotel());
  }
}
