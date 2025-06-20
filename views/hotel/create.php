<!DOCTYPE html>
<html><head><meta charset="utf-8"><script src="https://cdn.tailwindcss.com"></script><title>Nuevo Hotel</title></head>
<body class="p-6 max-w-md mx-auto">
  <h1 class="text-3xl mb-4">Agregar Hotel</h1>
  <form method="post" action="?ruta=hotel-guardar" class="space-y-4">
    <input name="nombre" placeholder="Nombre del hotel" class="border p-2 w-full" required>
    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Guardar</button>
  </form>
</body></html>
