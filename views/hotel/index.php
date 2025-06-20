<!DOCTYPE html>
<html><head><meta charset="utf-8"><script src="https://cdn.tailwindcss.com"></script><title>Hoteles</title></head>
<body class="p-6 max-w-2xl mx-auto">
  <h1 class="text-3xl mb-4">Gestión de Hoteles</h1>
  <a href="?ruta=hotel-nuevo" class="bg-blue-600 text-white px-4 py-2 rounded">Nuevo Hotel</a>
  <table class="min-w-full bg-white mt-4 border">
    <thead><tr class="bg-gray-200">
      <th class="p-2 border">ID</th>
      <th class="p-2 border">Nombre</th>
      <th class="p-2 border">Acciones</th>
    </tr></thead>
    <tbody>
      <?php foreach($lista as $h): ?>
        <tr>
          <td class="p-2 border"><?= $h['id'] ?></td>
          <td class="p-2 border"><?= htmlspecialchars($h['nombre']) ?></td>
          <td class="p-2 border space-x-2">
            <a href="?ruta=hotel-editar&id=<?= $h['id'] ?>" class="text-blue-600">Editar</a>
            <a href="?ruta=hotel-borrar&id=<?= $h['id'] ?>"
               onclick="return confirm('¿Borrar hotel <?= $h['nombre'] ?>?')"
               class="text-red-600">Borrar</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</body></html>
