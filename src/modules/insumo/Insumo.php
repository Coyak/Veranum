<?php
// src/modules/insumo/Insumo.php
namespace Modules\Insumo;

require_once __DIR__ . '/../../../config/database.php';

class Insumo {
    private $pdo;
    public function __construct() {
        $this->pdo = \Database::connect();
    }

    // Listar todos los insumos
    public function all(): array {
        $stmt = $this->pdo->query("SELECT * FROM insumos ORDER BY nombre ASC");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    // Crear nuevo insumo
    public function create(array $data): bool {
        $stmt = $this->pdo->prepare("INSERT INTO insumos (nombre, descripcion, unidad, stock_actual) VALUES (?, ?, ?, ?)");
        return $stmt->execute([
            trim($data['nombre']),
            trim($data['descripcion'] ?? ''),
            trim($data['unidad']),
            floatval($data['stock_actual'] ?? 0)
        ]);
    }

    // Editar insumo
    public function update(array $data): bool {
        $stmt = $this->pdo->prepare("UPDATE insumos SET nombre=?, descripcion=?, unidad=?, stock_actual=? WHERE id=?");
        return $stmt->execute([
            trim($data['nombre']),
            trim($data['descripcion'] ?? ''),
            trim($data['unidad']),
            floatval($data['stock_actual'] ?? 0),
            (int)$data['id']
        ]);
    }

    // Eliminar insumo
    public function delete(int $id): bool {
        $stmt = $this->pdo->prepare("DELETE FROM insumos WHERE id=?");
        return $stmt->execute([$id]);
    }

    // Registrar movimiento (ingreso o consumo)
    public function registrarMovimiento(array $data): bool {
        $stmt = $this->pdo->prepare("INSERT INTO movimientos_insumo (id_insumo, tipo_movimiento, cantidad, observacion) VALUES (?, ?, ?, ?)");
        $ok = $stmt->execute([
            (int)$data['id_insumo'],
            $data['tipo_movimiento'], // 'ingreso' o 'consumo'
            floatval($data['cantidad']),
            trim($data['observacion'] ?? '')
        ]);
        if ($ok) {
            // Actualizar stock
            $delta = ($data['tipo_movimiento'] === 'ingreso') ? floatval($data['cantidad']) : -floatval($data['cantidad']);
            $this->pdo->prepare("UPDATE insumos SET stock_actual = stock_actual + ? WHERE id = ?")
                ->execute([$delta, (int)$data['id_insumo']]);
        }
        return $ok;
    }

    // Listar movimientos
    public function movimientos(int $id_insumo = null): array {
        $sql = "SELECT m.*, i.nombre AS insumo_nombre, i.unidad FROM movimientos_insumo m JOIN insumos i ON m.id_insumo = i.id";
        $params = [];
        if ($id_insumo) {
            $sql .= " WHERE m.id_insumo = ?";
            $params[] = $id_insumo;
        }
        $sql .= " ORDER BY m.fecha DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
} 