<?php
require_once __DIR__ . '/../models/Cliente.php';

class ClienteController {
    private $model;
    public function __construct() {
        $this->model = new Cliente();
    }

    /**
     * Muestra el formulario de registro y procesa el POST,
     * mostrando errores o éxito directamente en la misma página.
     */
    public function register() {
        $error   = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nombre = trim($_POST['nombre'] ?? '');
        $email  = trim($_POST['email']  ?? '');
        $pass   = $_POST['password']    ?? '';

        // Validaciones simples antes de la BD
        if ($nombre === '' || $email === '' || $pass === '') {
            $error = 'Todos los campos son obligatorios.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'El correo no tiene un formato válido.';
        } else {
            try {
            // Intenta crear el cliente
            $hash = password_hash($pass, PASSWORD_DEFAULT);
            $this->model->create($nombre, $email, $hash);

            $success = '✅ Usuario registrado correctamente. '
                    . '<a href="?ruta=login" class="underline">Inicia sesión aquí</a>.';

            // Para que veas el mensaje y no reenvíe POST si refrescas:
            // despejamos $_POST
            $_POST = [];
            } catch (PDOException $e) {
            // Código 23000 = violación de integridad (duplicado)
            if ($e->getCode() === '23000') {
                $error = 'El correo <strong>'.htmlspecialchars($email).'</strong> ya está registrado.';
            } else {
                $error = 'Error al registrar el usuario: ' . $e->getMessage();
            }
            }
        }
        }

        // Renderizamos la vista, pasándole $error y $success
        include __DIR__ . '/../views/cliente/register.php';
    }

    /**
     * Muestra el formulario de login y procesa el POST,
     * mostrando error si las credenciales no coinciden.
     */
    public function login() {
    $error = '';
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = trim($_POST['email']  ?? '');
        $pass  = $_POST['password']     ?? '';
        $user  = $this->model->findByEmail($email);

        if ($user && password_verify($pass, $user['password'])) {
        //  ───────── Aquí guardamos ID y ROLE en la sesión ─────────
        $_SESSION['cliente_id']   = $user['id'];
        $_SESSION['cliente_role'] = $user['role'];
        // ────────────────────────────────────────────────────────────
        header('Location: ?ruta=dashboard');
        exit;
        } else {
        $error = 'Email o contraseña incorrectos.';
        }
    }
    include __DIR__ . '/../views/cliente/login.php';
    }


    public function logout() {
        session_destroy();
        header('Location: ?ruta=login');
        exit;
    }
}

