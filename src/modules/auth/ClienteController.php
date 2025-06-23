<?php
// src/modules/auth/ClienteController.php
namespace Modules\Auth;
require_once __DIR__ . '/Cliente.php';

class ClienteController {
    private $model;
    public function __construct() {
        $this->model = new Cliente();
    }
    public function register() {
        $in = json_decode(file_get_contents('php://input'), true);
        if (!$in || !isset($in['email'], $in['password'], $in['nombre'])) {
        echo json_encode(['ok'=>false,'error'=>'Parámetros faltantes']);
        return;
        }
        if ($this->model->create($in)) {
        echo json_encode(['ok'=>true]);
        } else {
        echo json_encode(['ok'=>false,'error'=>'No se pudo crear el usuario']);
        }
    }
    public function login() {
        $in = json_decode(file_get_contents('php://input'), true);
        if (!$in || !isset($in['email'], $in['password'])) {
        echo json_encode(['ok'=>false,'error'=>'Parámetros faltantes']);
        return;
        }
        $user = $this->model->findByEmail($in['email']);
        if ($user && password_verify($in['password'], $user['password'])) {
        $_SESSION['cliente_id']   = $user['id'];
        $_SESSION['cliente_role'] = $user['role'];
        echo json_encode(['ok' => true, 'id' => $user['id'], 'role' => $user['role']]);
        } else {
        echo json_encode(['ok' => false, 'error' => 'Email o contraseña inválidos']);
        }
    }
}
