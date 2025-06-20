<?php
require_once __DIR__ . '/../config/database.php';

class Cliente {
    private $pdo;
    public function __construct() {
        $this->pdo = Database::connect();
    }

    /**
     * Crea un nuevo cliente. Arroja PDOException en caso de error.
     */
    public function create($nombre, $email, $passwordHash) {
        $sql = "INSERT INTO clientes (nombre, email, password) VALUES (?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$nombre, $email, $passwordHash]);
    }

    public function findByEmail($email) {
        $sql = "SELECT * FROM clientes WHERE email = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

