<?php
// src/modules/auth/Cliente.php
namespace Modules\Auth;

// Aquí subimos TRES niveles: src/modules/auth → src/modules → src → veranum
require_once __DIR__ . '/../../../config/database.php';

class Cliente {
    private $pdo;
    public function __construct() {
        $this->pdo = \Database::connect();
    }
    public function findByEmail(string $email): ?array {
        $stmt = $this->pdo->prepare("SELECT * FROM clientes WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;
    }
    public function create(array $data): bool {
        $stmt = $this->pdo->prepare("
        INSERT INTO clientes (nombre,email,password,role)
        VALUES (?, ?, ?, 'cliente')
        ");
        $hash = password_hash($data['password'], PASSWORD_DEFAULT);
        return $stmt->execute([
        trim($data['nombre']),
        trim($data['email']),
        $hash
        ]);
    }
}
