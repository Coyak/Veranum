<?php
ini_set('display_errors','0');
error_reporting(0);

session_start();
header('Content-Type: application/json; charset=utf-8');

require __DIR__ . '/../config/database.php';
require __DIR__ . '/../src/modules/auth/ClienteController.php';
require __DIR__ . '/../src/modules/hotel/HotelController.php';
require __DIR__.'/../src/modules/habitacion/HabitacionController.php';
require __DIR__.'/../src/modules/reserva/ReservaController.php';


$api = $_GET['api'] ?? '';
switch($api) {
    case 'register':
        (new \Modules\Auth\ClienteController)->register();
        break;
    case 'login':
        (new \Modules\Auth\ClienteController)->login();
        break;
    default:
        http_response_code(404);
        echo json_encode(['ok'=>false,'error'=>'API no encontrada']);
    
    
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
    case 'reserva-servicio':
        (new \Modules\Reserva\ReservaController())->servicio();
        break;

}
