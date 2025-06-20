<?php
require_once __DIR__ . '/../config/database.php';

class Reserva {
    private $pdo;
    public function __construct() {
    $this->pdo = Database::connect();
}

public function allByCliente($cliente_id) {
    $sql = "SELECT r.*, h.tipo, ht.nombre AS hotel
            FROM reservas r
            JOIN habitaciones h ON r.habitacion_id = h.id
            JOIN hoteles ht ON h.hotel_id = ht.id
            WHERE r.cliente_id = ?
            ORDER BY r.fecha_inicio DESC";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([$cliente_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function find($id) {
    $stmt = $this->pdo->prepare("SELECT * FROM reservas WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

public function create($data) {
        $sql = "INSERT INTO reservas
                (cliente_id, habitacion_id, fecha_inicio, fecha_fin, servicios)
                VALUES (?, ?, ?, ?, ?)";
    $stmt = $this->pdo->prepare($sql);
    return $stmt->execute([
    $data['cliente_id'],
    $data['habitacion_id'],
    $data['fecha_inicio'],
    $data['fecha_fin'],
    $data['servicios'] ?? null
    ]);
}

public function update($id, $data) {
        $sql = "UPDATE reservas SET
                habitacion_id = ?, fecha_inicio = ?, fecha_fin = ?, servicios = ?
                WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
        $data['habitacion_id'],
        $data['fecha_inicio'],
        $data['fecha_fin'],
        $data['servicios'] ?? null,
        $id
        ]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM reservas WHERE id = ?");
        return $stmt->execute([$id]);
    }
    public function availableRooms($fecha_inicio, $fecha_fin) {
    $sql = "
        SELECT h.*
        FROM habitaciones h
        WHERE NOT EXISTS (
        SELECT 1 FROM reservas r
        WHERE r.habitacion_id = h.id
            AND r.fecha_inicio <= :fecha_fin
            AND r.fecha_fin    >= :fecha_inicio
        )
        ORDER BY h.id
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'fecha_inicio' => $fecha_inicio,
            'fecha_fin'    => $fecha_fin
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}


