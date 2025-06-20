<?php
class Database {
    private static $host = 'localhost';    // Cambiamos a 'localhost'
    private static $port = '3306';         // Puerto por defecto de MySQL en Laragon
    private static $db   = 'veranum';
    private static $user = 'root';
    private static $pass = '';
    public static function connect() {
        try {
        $dsn = "mysql:host=".self::$host.";port=".self::$port.";dbname=".self::$db.";charset=utf8";
        $pdo = new PDO($dsn, self::$user, self::$pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
        } catch (PDOException $e) {
        // Aquí veremos el mensaje exacto de error
        die("Error de conexión a la base de datos: " . $e->getMessage());
        }
    }
}
