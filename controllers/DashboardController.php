<?php
class DashboardController {
    public function index() {
        $role = $_SESSION['cliente_role'] ?? 'cliente';
        switch ($role) {
        case 'admin':
            header('Location: ?ruta=hoteles');       // Panel de Admin
            break;
        case 'recepcionista':
            header('Location: ?ruta=habitaciones');  // Panel de Recepción
            break;
        case 'cocinero':
            header('Location: ?ruta=insumos');       // Panel de Cocina
            break;
        case 'gerente':
            header('Location: ?ruta=reportes');      // Panel de Gerente
            break;
        default:
            header('Location: ?ruta=reservas');      // Panel Cliente
            break;
        }
        exit;
    }
}
