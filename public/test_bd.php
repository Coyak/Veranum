<?php
require __DIR__ . '/../config/database.php';

try {
    $pdo = Database::connect();
    echo '✅ Conexión exitosa a MySQL en puerto 3306.';
    } catch (Exception $e) {
    echo '❌ ' . $e->getMessage();
}
