<?php
// src/modules/servicio/ServicioController.php
namespace Modules\Servicio;

require_once __DIR__ . '/Servicio.php';

class ServicioController {
    private $model;

    public function __construct() {
        $this->model = new Servicio();
    }

    /**
     * Maneja la creación de un nuevo servicio.
     * Lee los datos del cuerpo de la solicitud POST.
     */
    public function store() {
        $in = json_decode(file_get_contents('php://input'), true);

        if (!$in || !isset($in['reserva_id'], $in['tipo'])) {
            http_response_code(400);
            echo json_encode(['ok' => false, 'error' => 'Parámetros incompletos. Se requiere reserva_id y tipo.']);
            return;
        }

        $ok = $this->model->create((int)$in['reserva_id'], $in['tipo']);

        if ($ok) {
            echo json_encode(['ok' => true, 'message' => 'Servicio registrado correctamente.']);
        } else {
            http_response_code(500);
            echo json_encode(['ok' => false, 'error' => 'No se pudo registrar el servicio.']);
        }
    }

    /**
     * Asigna múltiples servicios a una reserva.
     * Este método ahora SOLO AÑADE servicios. No borra los existentes.
     * Lee un array de servicios del cuerpo de la solicitud.
     */
    public function assign() {
        $in = json_decode(file_get_contents('php://input'), true);

        if (!$in || !isset($in['reserva_id']) || !isset($in['servicios']) || !is_array($in['servicios'])) {
            http_response_code(400);
            echo json_encode(['ok' => false, 'error' => 'Parámetros incorrectos. Se requiere reserva_id y un array de servicios.']);
            return;
        }

        $reserva_id = (int)$in['reserva_id'];
        $servicios = $in['servicios'];
        $all_ok = true;

        $this->model->pdo->beginTransaction(); // Iniciar transacción

        // Ya no se borran los servicios existentes. Solo se añaden los nuevos.
        foreach ($servicios as $servicio) {
            if (!isset($servicio['id']) || !isset($servicio['cantidad']) || !isset($servicio['precio'])) {
                $all_ok = false;
                break;
            }
            $ok = $this->model->createV2($reserva_id, $servicio);
            if (!$ok) {
                $all_ok = false;
                break;
            }
        }

        if ($all_ok) {
            $this->model->pdo->commit();
            echo json_encode(['ok' => true, 'message' => 'Servicios registrados correctamente.']);
        } else {
            $this->model->pdo->rollBack();
            http_response_code(500);
            echo json_encode(['ok' => false, 'error' => 'No se pudieron registrar todos los servicios.']);
        }
    }

    /**
     * Obtiene los servicios asignados a una reserva específica.
     * Lee el reserva_id de los parámetros GET.
     */
    public function getAssignedServices() {
        $reserva_id = $_GET['reserva_id'] ?? null;

        if (!$reserva_id) {
            http_response_code(400);
            echo json_encode(['ok' => false, 'error' => 'Se requiere el parámetro reserva_id.']);
            return;
        }

        $servicios = $this->model->getByReservaId((int)$reserva_id);

        if ($servicios !== false) {
            echo json_encode(['ok' => true, 'servicios' => $servicios]);
        } else {
            http_response_code(500);
            echo json_encode(['ok' => false, 'error' => 'No se pudieron obtener los servicios.']);
        }
    }

    /**
     * Elimina un registro de servicio específico.
     * Lee el id del servicio del cuerpo de la solicitud.
     */
    public function delete() {
        $in = json_decode(file_get_contents('php://input'), true);
        $id = $in['id'] ?? null;

        if (!$id) {
            http_response_code(400);
            echo json_encode(['ok' => false, 'error' => 'Se requiere el ID del servicio.']);
            return;
        }

        $ok = $this->model->delete((int)$id);

        if ($ok) {
            echo json_encode(['ok' => true, 'message' => 'Servicio eliminado correctamente.']);
        } else {
            http_response_code(500);
            echo json_encode(['ok' => false, 'error' => 'No se pudo eliminar el servicio.']);
        }
    }
}
