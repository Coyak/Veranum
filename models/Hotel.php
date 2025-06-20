<?php
require_once __DIR__ . '/../config/database.php';

class Hotel {
    private $pdo;
    public function __construct() {
        $this->pdo = Database::connect();
    }
    public function all() {
        return $this->pdo->query("SELECT * FROM hoteles ORDER BY nombre")
                        ->fetchAll(PDO::FETCH_ASSOC);
    }
    public function find($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM hoteles WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function create($nombre) {
        $stmt = $this->pdo->prepare("INSERT INTO hoteles (nombre) VALUES (?)");
        return $stmt->execute([$nombre]);
    }
    public function update($id, $nombre) {
        $stmt = $this->pdo->prepare("UPDATE hoteles SET nombre = ? WHERE id = ?");
        return $stmt->execute([$nombre, $id]);
    }
    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM hoteles WHERE id = ?");
        return $stmt->execute([$id]);
    }
    }

