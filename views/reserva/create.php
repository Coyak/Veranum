<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<script src="https://cdn.tailwindcss.com"></script>
<title>Nueva Reserva</title>
</head>
<body class="p-6 max-w-md mx-auto">
    <h1 class="text-3xl mb-4">Reservar Habitación</h1>
    <?php if(empty($habitaciones)): ?>
    <p class="text-red-600 mb-4">
        No hay habitaciones libres entre <?=htmlspecialchars($f1)?> y <?=htmlspecialchars($f2)?>.
    </p>
    <a href="?ruta=reserva-buscar" class="underline">Volver a buscar</a>
    <?php return; ?>
    <?php endif; ?>
        
    <form method="post" action="?ruta=reserva-guardar" class="space-y-4">
        <select name="habitacion_id" class="border p-2 w-full" required>
        <option value="">Selecciona habitación</option>
        <?php foreach($habitaciones as $h): ?>
            <option value="<?= $h['id'] ?>">
            <?= htmlspecialchars($h['hotel'])." – ".$h['tipo']." ($".number_format($h['precio'],2).")" ?>
            </option>
        <?php endforeach; ?>
        </select>

        <input name="fecha_inicio"
            type="date"
            class="border p-2 w-full"
            required>

        <input name="fecha_fin"
            type="date"
            class="border p-2 w-full"
            required>

        <textarea name="servicios"
                placeholder="Servicios adicionales"
                class="border p-2 w-full"></textarea>

        <button type="submit"
                class="bg-green-600 text-white px-4 py-2 rounded">
        Guardar Reserva
        </button>
    </form>
</body>
</html>
