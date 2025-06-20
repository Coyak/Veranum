<?php
session_start();
// Mostrar errores (útil en desarrollo)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Conexión y controladores
require __DIR__ . '/../config/database.php';
require __DIR__ . '/../controllers/ClienteController.php';
require __DIR__ . '/../controllers/HotelController.php';
require __DIR__ . '/../controllers/HabitacionController.php';
require __DIR__ . '/../controllers/ReservaController.php';

// Tomamos la ruta de la URL, por defecto 'login'
$route = $_GET['ruta'] ?? 'login';

// Debug: imprime la ruta que está usando el router
// echo "<!-- Ruta solicitada: $route -->";

switch ($route) {
  // Clientes
  case 'register':   (new ClienteController)->register();  break;
  case 'login':      (new ClienteController)->login();     break;
  case 'logout':     (new ClienteController)->logout();    break;

  // Hoteles
  case 'hoteles':        (new HotelController)->index();   break;
  case 'hotel-nuevo':    (new HotelController)->create();  break;
  case 'hotel-guardar':  (new HotelController)->store();   break;
  case 'hotel-editar':   (new HotelController)->edit();    break;
  case 'hotel-update':   (new HotelController)->update();  break;
  case 'hotel-borrar':   (new HotelController)->delete();  break;

  // Habitaciones
  case 'habitaciones':       (new HabitacionController)->index();   break;
  case 'habitacion-nueva':   (new HabitacionController)->create();  break;
  case 'habitacion-guardar': (new HabitacionController)->store();   break;
  case 'habitacion-editar':  (new HabitacionController)->edit();    break;
  case 'habitacion-update':  (new HabitacionController)->update();  break;
  case 'habitacion-borrar':  (new HabitacionController)->delete();  break;

  // Reservas
  case 'reservas':         (new ReservaController)->index();   break;
  case 'reserva-nueva':    (new ReservaController)->create();  break;
  case 'reserva-guardar':  (new ReservaController)->store();   break;
  case 'reserva-editar':   (new ReservaController)->edit();    break;
  case 'reserva-update':   (new ReservaController)->update();  break;
  case 'reserva-borrar':   (new ReservaController)->delete();  break;

  default:
    // Muestra la ruta solicitada para debug
    echo "Ruta no encontrada: <strong>" . htmlspecialchars($route) . "</strong>";
    break;
}
