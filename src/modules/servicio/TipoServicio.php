<?php
// src/modules/servicio/TipoServicio.php
namespace Modules\Servicio;

class TipoServicio {
    private $pdo;

    public function __construct() {
        $this->pdo = \Database::connect();
    }

    /**
     * Devuelve todos los tipos de servicio.
     */
    public function all(): array {
        $stmt = $this->pdo->query("SELECT * FROM tipos_servicio ORDER BY nombre ASC");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Crea un nuevo tipo de servicio.
     */
    public function create(array $data): bool {
        $sql = "INSERT INTO tipos_servicio (nombre, precio) VALUES (?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $data['nombre'],
            $data['precio']
        ]);
    }

    /**
     * Actualiza un tipo de servicio existente.
     */
    public function update(array $data): bool {
        $sql = "UPDATE tipos_servicio SET nombre = ?, precio = ? WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $data['nombre'],
            $data['precio'],
            $data['id']
        ]);
    }

    /**
     * Elimina un tipo de servicio por su ID.
     */
    public function delete(int $id): bool {
        $sql = "DELETE FROM tipos_servicio WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }
} 