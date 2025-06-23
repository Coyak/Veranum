<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json; charset=utf-8');

require __DIR__ . '/../config/database.php';

try {
    $pdo = Database::connect();
    
    // Leer el archivo SQL
    $sql = file_get_contents(__DIR__ . '/../database/schema.sql');
    
    // Ejecutar las consultas
    $pdo->exec($sql);
    
    echo json_encode([
        'ok' => true, 
        'message' => 'Base de datos configurada correctamente'
    ]);
    
} catch (Exception $e) {
    echo json_encode(['ok' => false, 'error' => 'Error: ' . $e->getMessage()]);
} 