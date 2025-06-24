<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Log para debugging
error_log("API llamada: " . ($_GET['api'] ?? 'no api'));

session_start();
header('Content-Type: application/json; charset=utf-8');

// Si es POST y el body es JSON, decodifica y agrega a $_POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($_POST)) {
    $input = json_decode(file_get_contents('php://input'), true);
    if (is_array($input)) {
        $_POST = $input;
    }
}

try {
    require __DIR__ . '/../config/database.php';
    require __DIR__ . '/../src/modules/auth/ClienteController.php';
    require __DIR__ . '/../src/modules/hotel/HotelController.php';
    require __DIR__.'/../src/modules/habitacion/HabitacionController.php';
    require __DIR__.'/../src/modules/reserva/ReservaController.php';
    require __DIR__.'/../src/modules/servicio/ServicioController.php';
    require __DIR__.'/../src/modules/servicio/TipoServicioController.php';
    require __DIR__.'/../src/modules/insumo/InsumoController.php';

    $api = $_GET['api'] ?? '';
    switch($api) {
        case 'register':
            (new \Modules\Auth\ClienteController)->register();
            break;
        case 'login':
            (new \Modules\Auth\ClienteController)->login();
            break;
        case 'hoteles':
            (new \Modules\Hotel\HotelController)->index();
            break;
        case 'hotel-create':
            (new \Modules\Hotel\HotelController)->store();
            break;
        case 'hotel-update':
            (new \Modules\Hotel\HotelController)->update();
            break;
        case 'hotel-delete':
            (new \Modules\Hotel\HotelController)->delete();
            break;

        // Habitaciones
        case 'habitaciones':
            (new \Modules\Habitacion\HabitacionController)->index();
            break;
        case 'habitacion-create':
            (new \Modules\Habitacion\HabitacionController)->store();
            break;
        case 'habitacion-update':
            (new \Modules\Habitacion\HabitacionController)->update();
            break;
        case 'habitacion-delete':
            (new \Modules\Habitacion\HabitacionController)->delete();
            break;
        case 'habitacion-disponibles':
            (new \Modules\Habitacion\HabitacionController())->available();
            break;

        // Reservas
        case 'reservas':
            (new \Modules\Reserva\ReservaController())->index();
            break;
        case 'reserva-create':
            (new \Modules\Reserva\ReservaController())->store();
            break;
        case 'reserva-update':
            (new \Modules\Reserva\ReservaController())->update();
            break;
        case 'reserva-delete':
            (new \Modules\Reserva\ReservaController())->delete();
            break;
        case 'reserva-checkin':
            (new \Modules\Reserva\ReservaController())->checkin();
            break;
        case 'reservas-checkin':
            (new \Modules\Reserva\ReservaController())->getCheckedIn();
            break;
        case 'asignar-servicios':
            (new \Modules\Servicio\ServicioController())->assign();
            break;
        case 'servicios-reserva':
            (new \Modules\Servicio\ServicioController())->getAssignedServices();
            break;
        case 'reserva-servicio':
            (new \Modules\Servicio\ServicioController())->store();
            break;
        case 'mis-reservas':
            (new \Modules\Reserva\ReservaController())->misReservas();
            break;
        case 'reservas-recepcion':
            (new \Modules\Reserva\ReservaController())->getForReception();
            break;
        case 'reserva-details':
            (new \Modules\Reserva\ReservaController())->getDetails();
            break;

        // CRUD para Tipos de Servicio (Admin)
        case 'tipos-servicio':
            (new \Modules\Servicio\TipoServicioController())->index();
            break;
        case 'tipos-servicio-create':
            (new \Modules\Servicio\TipoServicioController())->store();
            break;
        case 'tipos-servicio-update':
            (new \Modules\Servicio\TipoServicioController())->update();
            break;
        case 'tipos-servicio-delete':
            (new \Modules\Servicio\TipoServicioController())->delete();
            break;
        case 'servicio-delete':
            (new \Modules\Servicio\ServicioController())->delete();
            break;

        // Insumos (solo cocinero)
        case 'insumos':
            (new \Modules\Insumo\InsumoController())->index();
            break;
        case 'insumo-create':
            (new \Modules\Insumo\InsumoController())->store();
            break;
        case 'insumo-update':
            (new \Modules\Insumo\InsumoController())->update();
            break;
        case 'insumo-delete':
            (new \Modules\Insumo\InsumoController())->delete();
            break;
        case 'insumo-movimiento':
            (new \Modules\Insumo\InsumoController())->movimiento();
            break;
        case 'insumo-movimientos':
            (new \Modules\Insumo\InsumoController())->movimientos();
            break;

        // Reportes para gerente
        case 'reporte-ocupacion':
            (new \Modules\Habitacion\HabitacionController())->reporteOcupacion();
            break;
        case 'reporte-ingresos':
            $fecha_inicio = $_GET['fecha_inicio'] ?? $_POST['fecha_inicio'] ?? null;
            $fecha_fin = $_GET['fecha_fin'] ?? $_POST['fecha_fin'] ?? null;
            $res_hab = (new \Modules\Reserva\ReservaController())->reporteIngresos(false, $fecha_inicio, $fecha_fin);
            $res_serv = (new \Modules\Servicio\ServicioController())->reporteIngresos(false, $fecha_inicio, $fecha_fin);
            $total_hab = $res_hab['total_habitaciones'] ?? 0;
            $total_serv = $res_serv['total_servicios'] ?? 0;
            $total_general = $total_hab + $total_serv;
            echo json_encode([
                'total_general' => $total_general,
                'total_habitaciones' => $total_hab,
                'detalle_habitaciones' => $res_hab['detalle_habitaciones'] ?? [],
                'total_servicios' => $total_serv,
                'detalle_servicios' => $res_serv['detalle_servicios'] ?? []
            ]);
            break;

        default:
            http_response_code(404);
            echo json_encode(['ok'=>false,'error'=>'API no encontrada']);
            break;
    }
} catch (Exception $e) {
    error_log("Error en API: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['ok'=>false,'error'=>'Error interno del servidor: ' . $e->getMessage()]);
}
