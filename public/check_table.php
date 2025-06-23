<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json; charset=utf-8');

require __DIR__ . '/../config/database.php';

try {
    $pdo = Database::connect();
    
    // Verificar si la tabla clientes existe
    $stmt = $pdo->query("SHOW TABLES LIKE 'clientes'");
    $tableExists = $stmt->rowCount() > 0;
    
    if ($tableExists) {
        // Verificar estructura de la tabla
        $stmt = $pdo->query("DESCRIBE clientes");
        $columns = $stmt->fetchAll();
        
        echo json_encode([
            'ok' => true, 
            'table_exists' => true,
            'columns' => $columns
        ]);
    } else {
        echo json_encode([
            'ok' => false, 
            'error' => 'La tabla clientes no existe',
            'table_exists' => false
        ]);
    }
    
} catch (Exception $e) {
    echo json_encode(['ok' => false, 'error' => 'Error: ' . $e->getMessage()]);
} 