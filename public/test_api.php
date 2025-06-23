<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json; charset=utf-8');

require __DIR__ . '/../config/database.php';
require __DIR__ . '/../src/modules/auth/ClienteController.php';

try {
    // Probar conexión a base de datos
    $pdo = Database::connect();
    echo json_encode(['ok' => true, 'message' => 'Conexión a BD exitosa']);
} catch (Exception $e) {
    echo json_encode(['ok' => false, 'error' => 'Error de BD: ' . $e->getMessage()]);
} 