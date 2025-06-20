<?php
require_once __DIR__ . '/../config/database.php';

class Habitacion {
  private $pdo;
  public function __construct() {
    $this->pdo = Database::connect();
  }

  public function all() {
    return $this->pdo->query("
      SELECT h.*, ht.nombre AS hotel
      FROM habitaciones h
      JOIN hoteles ht ON h.hotel_id = ht.id
      ORDER BY h.id
    ")->fetchAll(PDO::FETCH_ASSOC);
  }

  public function find($id) {
    $stmt = $this->pdo->prepare("SELECT * FROM habitaciones WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function create($data) {
    $sql = "INSERT INTO habitaciones (hotel_id, tipo, capacidad, precio)
            VALUES (?, ?, ?, ?)";
    $stmt = $this->pdo->prepare($sql);
    return $stmt->execute([
      $data['hotel_id'],
      $data['tipo'],
      $data['capacidad'],
      $data['precio']
    ]);
  }

  public function update($id, $data) {
    $sql = "UPDATE habitaciones SET hotel_id = ?, tipo = ?, capacidad = ?, precio = ?
            WHERE id = ?";
    $stmt = $this->pdo->prepare($sql);
    return $stmt->execute([
      $data['hotel_id'],
      $data['tipo'],
      $data['capacidad'],
      $data['precio'],
      $id
    ]);
  }

  public function delete($id) {
    $stmt = $this->pdo->prepare("DELETE FROM habitaciones WHERE id = ?");
    return $stmt->execute([$id]);
  }
}
