<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <script src="https://cdn.tailwindcss.com"></script>
  <title>Mis Reservas</title>
</head>
<body class="p-6 max-w-4xl mx-auto">
  <h1 class="text-3xl mb-4">Mis Reservas</h1>
  <a href="?ruta=reserva-nueva" class="bg-blue-600 text-white px-4 py-2 rounded">
    Reservar Habitación
  </a>
  <table class="min-w-full bg-white mt-4 border">
    <thead>
      <tr class="bg-gray-200">
        <th class="p-2 border">ID</th>
        <th class="p-2 border">Hotel</th>
        <th class="p-2 border">Habitación</th>
        <th class="p-2 border">Desde</th>
        <th class="p-2 border">Hasta</th>
        <th class="p-2 border">Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($lista)): ?>
        <?php foreach($lista as $r): ?>
        <tr>
          <td class="p-2 border"><?= $r['id'] ?></td>
          <td class="p-2 border"><?= htmlspecialchars($r['hotel']) ?></td>
          <td class="p-2 border"><?= htmlspecialchars($r['tipo']) ?></td>
          <td class="p-2 border"><?= $r['fecha_inicio'] ?></td>
          <td class="p-2 border"><?= $r['fecha_fin'] ?></td>
          <td class="p-2 border space-x-2">
            <a href="?ruta=reserva-editar&id=<?= $r['id'] ?>" class="text-blue-600">Editar</a>
            <a href="?ruta=reserva-borrar&id=<?= $r['id'] ?>"
               onclick="return confirm('¿Eliminar reserva <?= $r['id'] ?>?')"
               class="text-red-600">Borrar</a>
          </td>
        </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr>
          <td colspan="6" class="p-4 text-center text-gray-600">
            No tienes reservas registradas.
          </td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</body>
</html>
