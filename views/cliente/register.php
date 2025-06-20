<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<script src="https://cdn.tailwindcss.com"></script>
<title>Registro de Usuario</title>
</head>
<body class="max-w-md mx-auto p-6">
    <h1 class="text-3xl mb-4">Crear Cuenta</h1>

    <!-- Mensajes de error -->
    <?php if (!empty($error)): ?>
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
        <?= $error ?>
        </div>
    <?php endif; ?>

    <!-- Mensaje de éxito -->
    <?php if (!empty($success)): ?>
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
        <?= $success ?>
        </div>
    <?php endif; ?>

    <form method="post" action="?ruta=register" class="space-y-4">
        <input name="nombre"
            value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>"
            placeholder="Nombre completo"
            class="border p-2 w-full" required>

        <input name="email"
            value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
            type="email"
            placeholder="Correo electrónico"
            class="border p-2 w-full" required>

        <input name="password"
            type="password"
            placeholder="Contraseña"
            class="border p-2 w-full" required>

        <button type="submit"
                class="bg-blue-600 text-white px-4 py-2 rounded">
        Registrar
        </button>
    </form>

    <p class="mt-4 text-sm">
        ¿Ya tienes cuenta?
        <a href="?ruta=login" class="text-blue-600 underline">Inicia sesión aquí</a>.
    </p>
</body>
</html>
