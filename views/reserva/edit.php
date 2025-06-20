<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <script src="https://cdn.tailwindcss.com"></script>
  <title>Editar Reserva</title>
</head>
<body class="p-6 max-w-md mx-auto">
  <h1 class="text-3xl mb-4">Editar Reserva #<?= $item['id'] ?></h1>
  <form method="post" action="?ruta=reserva-update" class="space-y-4">
    <input type="hidden" name="id" value="<?= $item['id'] ?>">

    <select name="habitacion_id" class="border p-2 w-full" required>
      <?php foreach($habitaciones as $h): ?>
        <option value="<?= $h['id'] ?>"
          <?= $h['id']==$item['habitacion_id'] ? 'selected' : '' ?>>
          <?= htmlspecialchars($h['hotel'])." – ".$h['tipo']." ($".number_format($h['precio'],2).")" ?>
        </option>
      <?php endforeach; ?>
    </select>

    <input name="fecha_inicio"
           type="date"
           value="<?= $item['fecha_inicio'] ?>"
           class="border p-2 w-full"
           required>

    <input name="fecha_fin"
           type="date"
           value="<?= $item['fecha_fin'] ?>"
           class="border p-2 w-full"
           required>

    <textarea name="servicios"
              class="border p-2 w-full"
    ><?= htmlspecialchars($item['servicios']) ?></textarea>

    <button type="submit"
            class="bg-yellow-600 text-white px-4 py-2 rounded">
      Actualizar Reserva
    </button>
  </form>
</body>
</html>
