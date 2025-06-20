<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <script src="https://cdn.tailwindcss.com"></script>
  <title>Nueva Habitación</title>
</head>
<body class="p-6 max-w-md mx-auto">
  <h1 class="text-3xl mb-4">Crear Habitación</h1>
  <form method="post" action="?ruta=habitacion-guardar" class="space-y-4">
    <select name="hotel_id" class="border p-2 w-full" required>
      <option value="">Selecciona un hotel</option>
      <?php foreach($hoteles as $hotel): ?>
        <option value="<?= $hotel['id'] ?>">
          <?= htmlspecialchars($hotel['nombre']) ?>
        </option>
      <?php endforeach; ?>
    </select>
    <input name="tipo"
           placeholder="Tipo (e.g. Simple)"
           class="border p-2 w-full"
           required>
    <input name="capacidad"
           type="number"
           placeholder="Capacidad"
           class="border p-2 w-full"
           required>
    <input name="precio"
           type="number"
           step="0.01"
           placeholder="Precio diario"
           class="border p-2 w-full"
           required>
    <button type="submit"
            class="bg-green-600 text-white px-4 py-2 rounded">
      Guardar
    </button>
  </form>
</body>
</html>
