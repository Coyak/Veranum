<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <script src="https://cdn.tailwindcss.com"></script>
  <title>Listado de Habitaciones</title>
</head>
<body class="p-6 max-w-4xl mx-auto">
  <h1 class="text-3xl mb-4">Listado de Habitaciones</h1>
  <a href="?ruta=habitacion-nueva" class="bg-blue-600 text-white px-4 py-2 rounded">
    Nueva Habitación
  </a>
  <table class="min-w-full bg-white mt-4 border">
    <thead>
      <tr class="bg-gray-200">
        <th class="p-2 border">ID</th>
        <th class="p-2 border">Hotel</th>
        <th class="p-2 border">Tipo</th>
        <th class="p-2 border">Capacidad</th>
        <th class="p-2 border">Precio</th>
        <th class="p-2 border">Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php if(!empty($lista)): ?>
        <?php foreach($lista as $h): ?>
          <tr>
            <td class="p-2 border"><?= $h['id'] ?></td>
            <td class="p-2 border"><?= htmlspecialchars($h['hotel']) ?></td>
            <td class="p-2 border"><?= htmlspecialchars($h['tipo']) ?></td>
            <td class="p-2 border"><?= $h['capacidad'] ?></td>
            <td class="p-2 border">$ <?= number_format($h['precio'],2) ?></td>
            <td class="p-2 border space-x-2">
              <a href="?ruta=habitacion-editar&id=<?= $h['id'] ?>" class="text-blue-600">Editar</a>
              <a href="?ruta=habitacion-borrar&id=<?= $h['id'] ?>"
                 onclick="return confirm('¿Borrar habitación <?= $h['id'] ?>?')"
                 class="text-red-600">Borrar</a>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr>
          <td colspan="6" class="p-4 text-center text-gray-600">
            No hay habitaciones registradas.
          </td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</body>
</html>
