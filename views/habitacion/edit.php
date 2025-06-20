<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <script src="https://cdn.tailwindcss.com"></script>
  <title>Editar Habitación</title>
</head>
<body class="p-6 max-w-md mx-auto">
  <h1 class="text-3xl mb-4">Editar Habitación #<?= $item['id'] ?></h1>
  <form method="post" action="?ruta=habitacion-update" class="space-y-4">
    <input type="hidden" name="id" value="<?= $item['id'] ?>">
    <select name="hotel_id" class="border p-2 w-full" required>
      <?php foreach($hoteles as $hotel): ?>
        <option value="<?= $hotel['id'] ?>"
          <?= $hotel['id']==$item['hotel_id'] ? 'selected' : '' ?>>
          <?= htmlspecialchars($hotel['nombre']) ?>
        </option>
      <?php endforeach; ?>
    </select>
    <input name="tipo"
           value="<?= htmlspecialchars($item['tipo']) ?>"
           class="border p-2 w-full"
           required>
    <input name="capacidad"
           type="number"
           value="<?= $item['capacidad'] ?>"
           class="border p-2 w-full"
           required>
    <input name="precio"
           type="number"
           step="0.01"
           value="<?= $item['precio'] ?>"
           class="border p-2 w-full"
           required>
    <button type="submit"
            class="bg-yellow-600 text-white px-4 py-2 rounded">
      Actualizar
    </button>
  </form>
</body>
</html>
