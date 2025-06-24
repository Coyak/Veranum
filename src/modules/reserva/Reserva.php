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
      VALUES (?,?,?,?, 'pendiente')
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

  /** 7) Encontrar reservas por ID de cliente */
  public function findByClientId(int $cliente_id): array {
    $sql = "
      SELECT 
        r.id, 
        r.fecha_inicio, 
        r.fecha_fin, 
        r.status,
        h.nombre AS nombre_habitacion,
        h.foto,
        hot.nombre AS nombre_hotel
      FROM reservas r
      JOIN habitaciones h ON r.habitacion_id = h.id
      JOIN hoteles hot ON h.hotel_id = hot.id
      WHERE r.cliente_id = ?
      ORDER BY r.fecha_inicio DESC
    ";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([$cliente_id]);
    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
  }

  /** 8) Encontrar reservas por un estado específico */
  public function findByStatus(string $status): array {
    $sql = "
      SELECT 
        r.id, 
        r.fecha_inicio, 
        r.fecha_fin, 
        r.status,
        c.nombre AS cliente_nombre,
        h.nombre AS nombre_habitacion,
        h.precio AS precio_habitacion
      FROM reservas r
      JOIN clientes c ON r.cliente_id = c.id
      JOIN habitaciones h ON r.habitacion_id = h.id
      WHERE r.status = ?
      ORDER BY r.fecha_inicio ASC
    ";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([$status]);
    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
  }

  /** 9) Encontrar reservas para la recepción (pendientes o con check-in) */
  public function findForReception(): array {
    $sql = "
      SELECT 
        r.id, 
        r.fecha_inicio, 
        r.fecha_fin, 
        r.status,
        c.nombre AS cliente_nombre,
        h.nombre AS nombre_habitacion,
        h.precio AS precio_habitacion
      FROM reservas r
      JOIN clientes c ON r.cliente_id = c.id
      JOIN habitaciones h ON r.habitacion_id = h.id
      WHERE r.status IN ('pendiente', 'checkin')
      ORDER BY r.fecha_inicio ASC
    ";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
  }

  /** 10) Encontrar una reserva específica por su ID */
  public function findById(int $id) {
    // Reutiliza la consulta de findByStatus pero para un solo ID
    $sql = "
      SELECT 
        r.id, 
        r.fecha_inicio, 
        r.fecha_fin, 
        r.status,
        c.nombre AS cliente_nombre,
        h.nombre AS nombre_habitacion,
        h.precio AS precio_habitacion
      FROM reservas r
      JOIN clientes c ON r.cliente_id = c.id
      JOIN habitaciones h ON r.habitacion_id = h.id
      WHERE r.id = ?
    ";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([$id]);
    return $stmt->fetch(\PDO::FETCH_ASSOC);
  }

  /**
   * Devuelve el total de ingresos por habitaciones (reservas con status 'checkin' o 'ocupada').
   */
  public function totalIngresos($fecha_inicio = null, $fecha_fin = null): float {
    $where = "";
    $params = [];
    if ($fecha_inicio && $fecha_fin) {
      $where .= " AND r.fecha_inicio <= ? AND r.fecha_fin >= ?";
      $params[] = $fecha_fin;
      $params[] = $fecha_inicio;
    } elseif ($fecha_inicio) {
      $where .= " AND r.fecha_fin >= ?";
      $params[] = $fecha_inicio;
    } elseif ($fecha_fin) {
      $where .= " AND r.fecha_inicio <= ?";
      $params[] = $fecha_fin;
    }
    $sql = "SELECT SUM(h.precio) as total
            FROM reservas r
            JOIN habitaciones h ON r.habitacion_id = h.id
            WHERE r.status IN ('checkin','ocupada') $where";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute($params);
    $row = $stmt->fetch(\PDO::FETCH_ASSOC);
    return (float)($row['total'] ?? 0);
  }

  /**
   * Devuelve los ingresos generados por cada habitación (solo reservas checkin/ocupada).
   */
  public function ingresosPorHabitacion($fecha_inicio = null, $fecha_fin = null): array {
    $where = "";
    $params = [];
    if ($fecha_inicio && $fecha_fin) {
      $where .= " AND r.fecha_inicio <= ? AND r.fecha_fin >= ?";
      $params[] = $fecha_fin;
      $params[] = $fecha_inicio;
    } elseif ($fecha_inicio) {
      $where .= " AND r.fecha_fin >= ?";
      $params[] = $fecha_inicio;
    } elseif ($fecha_fin) {
      $where .= " AND r.fecha_inicio <= ?";
      $params[] = $fecha_fin;
    }
    $sql = "SELECT h.nombre as habitacion, SUM(h.precio) as monto
            FROM reservas r
            JOIN habitaciones h ON r.habitacion_id = h.id
            WHERE r.status IN ('checkin','ocupada') $where
            GROUP BY h.id, h.nombre
            ORDER BY monto DESC";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
  }
}
