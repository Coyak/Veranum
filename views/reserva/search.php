<!DOCTYPE html>
<html><head><meta charset="utf-8"><script src="https://cdn.tailwindcss.com"></script><title>Buscar Disponibilidad</title></head>
<body class="p-6 max-w-md mx-auto">
  <h1 class="text-3xl mb-4">Buscar habitación</h1>
  <form method="get" action="?ruta=reserva-nueva" class="space-y-4">
    <input name="fecha_inicio" type="date" class="border p-2 w-full" required>
    <input name="fecha_fin"    type="date" class="border p-2 w-full" required>
    <button class="bg-blue-600 text-white px-4 py-2 rounded">Buscar</button>
  </form>
</body>
</html>
