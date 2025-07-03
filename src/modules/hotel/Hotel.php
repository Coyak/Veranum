<?php
namespace Modules\Hotel;
// Ajusta el require para llegar a config/
require_once __DIR__ . '/../../../config/database.php';

class Hotel {
  private $pdo;
  public function __construct() {
    $this->pdo = \Database::connect();
  }

  public function all(): array {
    return $this->pdo
      ->query("SELECT * FROM hoteles ORDER BY nombre")
      ->fetchAll(\PDO::FETCH_ASSOC);
  }

  public function create(array $data): bool {
    $stmt = $this->pdo->prepare("INSERT INTO hoteles (nombre) VALUES (?)");
    return $stmt->execute([trim($data['nombre'])]);
  }

  public function update(int $id, array $data): bool {
    $stmt = $this->pdo->prepare("UPDATE hoteles SET nombre = ? WHERE id = ?");
    return $stmt->execute([trim($data['nombre']), $id]);
  }

  public function delete(int $id): bool {
    $stmt = $this->pdo->prepare("DELETE FROM hoteles WHERE id = ?");
    return $stmt->execute([$id]);
  }

  /**
   * Devuelve la cantidad de habitaciones ocupadas por hotel.
   */
  public function getOcupacionPorHotel(): array {
    $sql = "SELECT hot.id, hot.nombre as hotel, COUNT(CASE WHEN r.status IN ('checkin','ocupada') THEN 1 END) as ocupadas
            FROM hoteles hot
            LEFT JOIN habitaciones h ON h.hotel_id = hot.id
            LEFT JOIN reservas r ON r.habitacion_id = h.id
            GROUP BY hot.id, hot.nombre
            ORDER BY hot.nombre";
    $stmt = $this->pdo->query($sql);
    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
  }
}
