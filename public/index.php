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
require __DIR__ . '/../controllers/DashboardController.php';



// Tomamos la ruta de la URL, por defecto 'landing'
$route = $_GET['ruta'] ?? 'landing';

// Debug: imprime la ruta que está usando el router
// echo "<!-- Ruta solicitada: $route -->";
// Rutas públicas
$public = ['landing','login','register'];

// rutas públicas
$public = ['landing','login','register'];
// obliga a login si ruta no es pública
if (!in_array($route, $public) && empty($_SESSION['cliente_id'])) {
  header('Location: ?ruta=landing');
  exit;
}

switch ($route) {
  // Página de inicio
  case 'landing':
  include __DIR__ . '/../views/landing.php';
  break;

  // Dashboard
  case 'dashboard': (new DashboardController)->index(); break;
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

  case 'reservas':
    (new ReservaController)->index();
    break;
  case 'reserva-buscar':
    (new ReservaController)->search();
    break;
  case 'reserva-nueva':
    (new ReservaController)->create();
    break;
  case 'reserva-guardar':
    (new ReservaController)->store();
    break;

  

  


  default:
    // Muestra la ruta solicitada para debug
    echo "Ruta no encontrada: <strong>" . htmlspecialchars($route) . "</strong>";
    break;
}
