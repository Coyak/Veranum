<?php
require __DIR__ . '/config/database.php';

try {
    $pdo = Database::connect();
    echo "<h2 style='color:green;'>✅ Conexión exitosa a la base de datos veranum</h2>";
    } catch (Exception $e) {
    echo "<h2 style='color:red;'>❌ " . $e->getMessage() . "</h2>";
}
