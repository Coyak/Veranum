<?php
namespace Modules\Habitacion;
require_once __DIR__ . '/../../../config/database.php';

class Habitacion {
  private $pdo;
  public function __construct() {
    $this->pdo = \Database::connect();
  }

  // Listar por hotel
  public function allByHotel(int $hotel_id): array {
    $stmt = $this->pdo->prepare(
      "SELECT * FROM habitaciones WHERE hotel_id = ? ORDER BY nombre"
    );
    $stmt->execute([$hotel_id]);
    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
  }

  // Crear nueva
  public function create(array $data): bool {
    $stmt = $this->pdo->prepare("
      INSERT INTO habitaciones (hotel_id,nombre,precio,foto)
      VALUES (?,?,?,?)
    ");
    return $stmt->execute([
      (int)$data['hotel_id'],
      trim($data['nombre']),
      (float)$data['precio'],
      trim($data['foto'] ?? '')
    ]);
  }

  // Actualizar existente
  public function update(int $id, array $data): bool {
    $stmt = $this->pdo->prepare("
      UPDATE habitaciones
      SET nombre = ?, precio = ?, foto = ?
      WHERE id = ?
    ");
    return $stmt->execute([
      trim($data['nombre']),
      (float)$data['precio'],
      trim($data['foto'] ?? ''),
      $id
    ]);
  }

  // Borrar
  public function delete(int $id): bool {
    $stmt = $this->pdo->prepare("DELETE FROM habitaciones WHERE id = ?");
    return $stmt->execute([$id]);
  }
  public function all(): array {
    $stmt = $this->pdo->query("SELECT * FROM habitaciones ORDER BY id");
    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
  }
}
