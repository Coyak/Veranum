<?php
// src/modules/reserva/Reserva.php
namespace Modules\Reserva;
require_once __DIR__ . '/../../../config/database.php';

class Reserva {
  private $pdo;
  public function __construct() {
    $this->pdo = \Database::connect();
  }

  /** 1) Listar todas las reservas, con JOIN para traer nombre de cliente y habitación */
  public function all(): array {
    $sql = "
      SELECT r.id, r.cliente_id, c.nombre AS cliente_nombre,
             r.habitacion_id, h.nombre AS hab_nombre,
             r.fecha_inicio, r.fecha_fin, r.status
      FROM reservas r
      INNER JOIN clientes c ON r.cliente_id = c.id
      INNER JOIN habitaciones h ON r.habitacion_id = h.id
      ORDER BY r.fecha_inicio DESC
    ";
    return $this->pdo->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
  }

  /** 2) Crear nueva reserva */
  public function create(array $d): bool {
    $stmt = $this->pdo->prepare("
      INSERT INTO reservas 
        (cliente_id, habitacion_id, fecha_inicio, fecha_fin, status)
      VALUES (?,?,?,?, 'reservada')
    ");
    return $stmt->execute([
      (int)$d['cliente_id'],
      (int)$d['habitacion_id'],
      $d['fecha_inicio'],
      $d['fecha_fin']
    ]);
  }

  /** 3) Actualizar datos de reserva */
  public function update(array $d): bool {
    $stmt = $this->pdo->prepare("
      UPDATE reservas
      SET habitacion_id = ?, fecha_inicio = ?, fecha_fin = ?
      WHERE id = ?
    ");
    return $stmt->execute([
      (int)$d['habitacion_id'],
      $d['fecha_inicio'],
      $d['fecha_fin'],
      (int)$d['id']
    ]);
  }

  /** 4) Borrar reserva */
  public function delete(int $id): bool {
    $stmt = $this->pdo->prepare("DELETE FROM reservas WHERE id = ?");
    return $stmt->execute([$id]);
  }

  /** 5) Check-in: cambiar status a "ocupada" */
  public function setStatus(int $id, string $status): bool {
    $stmt = $this->pdo->prepare("UPDATE reservas SET status = ? WHERE id = ?");
    return $stmt->execute([$status, $id]);
  }

  /** 6) Registrar servicio extra (aquí solo un ejemplo: append texto a campo servicios) */
  public function addService(int $id, string $serv): bool {
    // Asegúrate de tener columna `servicios` tipo TEXT en reservas
    $stmt = $this->pdo->prepare("
      UPDATE reservas
      SET servicios = CONCAT(
        IFNULL(servicios, ''), 
        CHAR(10), ?, 
        ' (',NOW(),')'
      )
      WHERE id = ?
    ");
    return $stmt->execute([$serv, $id]);
  }
}
