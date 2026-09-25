<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Carga la configuración (verifica si existe en admin/config/ o en la raíz ../config/)
if (file_exists(__DIR__ . '/config/config.php')) {
    require_once __DIR__ . '/config/config.php';
} elseif (file_exists(__DIR__ . '/../config/config.php')) {
    require_once __DIR__ . '/../config/config.php';
} else {
    die("Error: No se encontró el archivo de configuración de la base de datos.");
}

// Funciones auxiliares de seguridad
if (!function_exists('e')) {
    function e(?string $value): string {
        return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('redirect')) {
    function redirect(string $url): void {
        header('Location: ' . $url);
        exit;
    }
}

// Si el usuario ya está autenticado, lo redirige al dashboard del admin
if (isset($_SESSION['user_id'])) {
    redirect('index.php');
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = (string)($_POST['password'] ?? '');

    try {
        // Conexión MySQLi mediante la función helper db()
        $stmt = db()->prepare('SELECT id, nombres, ap_paterno, ap_materno, email, password_hash, rol FROM usuarios WHERE email = ? LIMIT 1');
        
        if (!$stmt) {
            throw new Exception("Error al preparar la consulta.");
        }

        $stmt->bind_param('s', $email);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();

        if ($user && (password_verify($password, $user['password_hash']) || hash_equals($user['password_hash'], $password))) {
            session_regenerate_id(true);
            
            $nombreCompleto = trim($user['nombres'] . ' ' . $user['ap_paterno'] . ' ' . ($user['ap_materno'] ?? ''));
            
            $_SESSION['user_id'] = (int)$user['id'];
            $_SESSION['user_nombre'] = $nombreCompleto;
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_rol'] = $user['rol'];
            
            redirect('index.php');
        }
        $error = 'Correo electrónico o contraseña incorrectos.';
    } catch (Throwable $ex) {
        $error = 'No se pudo conectar con la base de datos. Verifica la configuración en config/config.php.';
    }
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Iniciar sesión · Sistema de Noticias Admin</title>
  <script>
    !function(){
      try {
        var t = localStorage.getItem("dash26-theme"),
            e = window.matchMedia("(prefers-color-scheme: dark)").matches;
        document.documentElement.setAttribute("data-theme", t || (e ? "dark" : "light"));
      } catch(t) {
        document.documentElement.setAttribute("data-theme", "light");
      }
    }()
  </script>
  <!-- Rutas relativas seguras para los archivos de estilo dentro de admin -->
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="app.css">
</head>
<body>
  <div class="login-page">
    <div class="login-card">
      <div class="login-brand" style="text-align: center;">
        <!-- Verificación de logo dentro del módulo admin o raíz -->
        <img src="assets/static/images/logo-gradient.svg" 
             onerror="this.onerror=null; this.src='../assets/images/logo.png';" 
             alt="Logo" 
             style="margin: 0 auto 16px auto; display: block; width: 56px; height: 56px;">
        <h1>Sistema de Noticias</h1>
        <p>Panel de administración</p>
      </div>

      <?php if ($error): ?>
        <div class="login-error" style="background-color: #fee2e2; color: #dc2626; padding: 10px; border-radius: 6px; margin-bottom: 16px; text-align: center; font-size: 14px;">
            <?= e($error) ?>
        </div>
      <?php endif; ?>

      <form method="post" action="login.php" autocomplete="on">
        <div class="field" style="margin-bottom: 14px;">
          <label class="field-label" for="email">Correo electrónico</label>
          <input class="input" id="email" name="email" type="email" required autofocus placeholder="usuario@dominio.com" value="<?= e($_POST['email'] ?? '') ?>">
        </div>

        <div class="field" style="margin-bottom: 18px;">
          <label class="field-label" for="password">Contraseña</label>
          <input class="input" id="password" name="password" type="password" required placeholder="contraseña">
        </div>

        <button class="btn btn--primary" type="submit" style="width: 100%; justify-content: center;">
          Ingresar al panel
        </button>
      </form>

    </div>
  </div>
</body>
</html>