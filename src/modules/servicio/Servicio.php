<?php
// src/modules/servicio/Servicio.php
namespace Modules\Servicio;

class Servicio {
    public $pdo;

    public function __construct() {
        // La clase Database debe estar disponible globalmente o ser incluida.
        // Asumimos que api.php ya la ha cargado.
        $this->pdo = \Database::connect();
    }

    /**
     * Crea un nuevo registro de servicio para una reserva.
     * @param int $reserva_id El ID de la reserva a la que se asocia el servicio.
     * @param string $tipo El tipo de servicio a registrar (ej. 'spa', 'lavanderia').
     * @return bool Devuelve true si la creación fue exitosa, false en caso contrario.
     */
    public function create(int $reserva_id, string $tipo): bool {
        $sql = "INSERT INTO servicios (reserva_id, tipo) VALUES (?, ?)";
        try {
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$reserva_id, $tipo]);
        } catch (\PDOException $e) {
            // En un entorno de producción, esto debería ser registrado en un archivo de logs.
            error_log("Error al crear servicio: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Crea un nuevo registro de servicio para una reserva, usando la nueva estructura.
     */
    public function createV2(int $reserva_id, array $servicio_data): bool {
        $sql = "INSERT INTO servicios (reserva_id, tipo_servicio_id, cantidad, precio_unitario) VALUES (?, ?, ?, ?)";
        try {
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([
                $reserva_id,
                (int)$servicio_data['id'],
                (int)$servicio_data['cantidad'],
                $servicio_data['precio'] // Ya viene como decimal
            ]);
        } catch (\PDOException $e) {
            error_log("Error en createV2: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtiene todos los servicios asignados a una reserva específica.
     * @param int $reserva_id El ID de la reserva.
     * @return array|false Devuelve un array de servicios o false si hay error.
     */
    public function getByReservaId(int $reserva_id) {
        $sql = "SELECT s.id, s.cantidad, s.precio_unitario, s.tipo_servicio_id, ts.nombre, ts.precio as precio_catalogo
                FROM servicios s
                JOIN tipos_servicio ts ON s.tipo_servicio_id = ts.id
                WHERE s.reserva_id = ?
                ORDER BY ts.nombre";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$reserva_id]);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Error al obtener servicios por reserva: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Elimina un registro de servicio por su ID único.
     * @param int $id El ID del registro en la tabla `servicios`.
     * @return bool Devuelve true si la eliminación fue exitosa, false en caso contrario.
     */
    public function delete(int $id): bool {
        $sql = "DELETE FROM servicios WHERE id = ?";
        try {
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$id]);
        } catch (\PDOException $e) {
            error_log("Error al eliminar servicio por ID: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Elimina todos los servicios asociados a una reserva.
     * @param int $reserva_id El ID de la reserva.
     * @return bool Devuelve true si la eliminación fue exitosa, false en caso contrario.
     */
    public function deleteByReservaId(int $reserva_id): bool {
        $sql = "DELETE FROM servicios WHERE reserva_id = ?";
        try {
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$reserva_id]);
        } catch (\PDOException $e) {
            error_log("Error al eliminar servicios por reserva: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Devuelve el total de ingresos por servicios.
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
        $sql = "SELECT SUM(s.cantidad * s.precio_unitario) as total
                FROM servicios s
                JOIN reservas r ON s.reserva_id = r.id
                WHERE 1=1 $where";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return (float)($row['total'] ?? 0);
    }

    /**
     * Devuelve los ingresos generados por cada tipo de servicio.
     */
    public function ingresosPorTipo($fecha_inicio = null, $fecha_fin = null): array {
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
        $sql = "SELECT ts.nombre as servicio, SUM(s.cantidad * s.precio_unitario) as monto
                FROM servicios s
                JOIN tipos_servicio ts ON s.tipo_servicio_id = ts.id
                JOIN reservas r ON s.reserva_id = r.id
                WHERE 1=1 $where
                GROUP BY ts.id, ts.nombre
                ORDER BY monto DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Devuelve los servicios más solicitados y su cantidad.
     */
    public function serviciosPopulares(): array {
        $sql = "SELECT ts.nombre as servicio, SUM(s.cantidad) as solicitudes
                FROM servicios s
                JOIN tipos_servicio ts ON s.tipo_servicio_id = ts.id
                GROUP BY ts.id, ts.nombre
                ORDER BY solicitudes DESC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
