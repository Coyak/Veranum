<?php
// config/database.php
class Database {
    private static $pdo;
    public static function connect() {
        if (!self::$pdo) {
        $dsn = sprintf(
            'mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4',
            '127.0.0.1',   // o 'localhost'
            3306,          // puerto de MySQL
            'veranum'      // tu base de datos
        );
        $user = 'root'; // usuario MySQL en Laragon
        $pass = '';     // contraseña (por defecto en Laragon es vacía)
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

