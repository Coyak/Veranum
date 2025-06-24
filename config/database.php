<?php
// config/database.php
class Database {
    private static $pdo;
    public static function connect() {
        if (!self::$pdo) {
            // --- CONFIGURACIÓN ---
            // Cambia la variable $modo a 'docker' o 'laragon' según el entorno que uses
            $modo = 'docker'; // 'docker' o 'laragon'

            if ($modo === 'docker') {
                // Configuración para Docker
                $host = 'db';
                $port = 3306;
                $db   = 'veranum';
                $user = 'veranum';
                $pass = 'veranum';
            } else {
                // Configuración para Laragon
                $host = '127.0.0.1'; // o 'localhost'
                $port = 3306;
                $db   = 'veranum';
                $user = 'root';
                $pass = '';
            }

            $dsn = sprintf(
                'mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4',
                $host,
                $port,
                $db
            );
            try {
                self::$pdo = new PDO($dsn, $user, $pass, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]);
            } catch (PDOException $e) {
                // En caso de error detallo el mensaje (para desarrollo)
                die('Error de Conexión: ' . $e->getMessage());
            }
        }
        return self::$pdo;
    }
}

