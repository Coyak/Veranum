<!DOCTYPE html>
<html><head><meta charset="utf-8"><script src="https://cdn.tailwindcss.com"></script><title>Editar Hotel</title></head>
<body class="p-6 max-w-md mx-auto">
  <h1 class="text-3xl mb-4">Editar Hotel #<?= $item['id'] ?></h1>
  <form method="post" action="?ruta=hotel-update" class="space-y-4">
    <input type="hidden" name="id" value="<?= $item['id'] ?>">
    <input name="nombre"
           value="<?= htmlspecialchars($item['nombre']) ?>"
           class="border p-2 w-full" required>
    <button type="submit" class="bg-yellow-600 text-white px-4 py-2 rounded">Actualizar</button>
  </form>
</body></html>
