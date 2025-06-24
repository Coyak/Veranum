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
    /**
   * Devuelve habitaciones libres entre dos fechas.
   */
  public function available(string $fi, string $ff): array {
    $sql = "
      SELECT h.* 
      FROM habitaciones h
      WHERE NOT EXISTS (
        SELECT 1 
        FROM reservas r 
        WHERE r.habitacion_id = h.id 
          AND r.fecha_inicio <= :ff 
          AND r.fecha_fin    >= :fi
      )
      ORDER BY h.id
    ";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([
      'fi' => $fi,
      'ff' => $ff
    ]);
    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
  }

  public function findAvailable(string $fecha_inicio, string $fecha_fin): array
  {
    $sql = "
      SELECT 
        h.id,
        h.nombre AS nombre_habitacion,
        h.precio,
        h.foto,
        hot.nombre AS nombre_hotel
      FROM habitaciones h
      JOIN hoteles hot ON h.hotel_id = hot.id
      WHERE h.id NOT IN (
        SELECT r.habitacion_id
        FROM reservas r
        WHERE 
          -- Se solapa si la reserva empieza durante el rango de búsqueda
          (r.fecha_inicio <= :fi AND r.fecha_fin > :fi) OR
          -- Se solapa si la reserva termina durante el rango de búsqueda
          (r.fecha_inicio < :ff AND r.fecha_fin >= :ff) OR
          -- Se solapa si la reserva contiene completamente el rango de búsqueda
          (r.fecha_inicio >= :fi AND r.fecha_fin <= :ff)
      )
    ";

    try {
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute([':fi' => $fecha_inicio, ':ff' => $fecha_fin]);
      return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    } catch (\PDOException $e) {
      // En un caso real, aquí se debería loguear el error.
      error_log('Error en findAvailable: ' . $e->getMessage());
      return [];
    }
  }

  /**
   * Devuelve todas las habitaciones con su estado de ocupación (ocupada/libre).
   */
  public function getOcupacion(): array {
    $sql = "SELECT h.id, h.nombre, 
      EXISTS (
        SELECT 1 FROM reservas r 
        WHERE r.habitacion_id = h.id AND r.status IN ('checkin','ocupada')
      ) AS ocupada
      FROM habitaciones h
      ORDER BY h.id";
    $stmt = $this->pdo->query($sql);
    $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    foreach ($data as &$hab) {
      $hab['ocupada'] = !!$hab['ocupada'];
    }
    return $data;
  }
}
