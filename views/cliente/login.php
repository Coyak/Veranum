<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <script src="https://cdn.tailwindcss.com"></script>
  <title>Login</title>
</head>
<body class="max-w-md mx-auto p-6">
  <h1 class="text-3xl mb-4">Iniciar Sesión</h1>

  <?php if (!empty($error)): ?>
    <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
      <?= $error ?>
    </div>
  <?php endif; ?>

  <form method="post" action="?ruta=login" class="space-y-4">
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
            class="bg-green-600 text-white px-4 py-2 rounded">
      Entrar
    </button>
  </form>

  <p class="mt-4 text-sm">
    ¿No tienes cuenta?
    <a href="?ruta=register" class="text-blue-600 underline">Regístrate aquí</a>.
  </p>
</body>
</html>
