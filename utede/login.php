<?php
/**
 * login.php
 * Controlador de inicio de sesion.
 * Sanitiza, valida, verifica credenciales y gestiona la sesion.
 */

session_start();

require_once __DIR__ . '/includes/sanitizar.php';
require_once __DIR__ . '/includes/validar.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/models/UsuarioModel.php';

// Si ya hay sesion activa, redirigir al panel principal
if (!empty($_SESSION['id_usuario'])) {
    header('Location: index.php');
    exit;
}

$errores = [];
$usuario = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // 1. Sanitizar
        $usuario = sanitizarTexto($_POST['usuario'] ?? '');
        $clave   = $_POST['clave'] ?? '';   // La clave NO se sanitiza para no alterar el hash

        // 2. Validar campos requeridos
        $errores = recopilarErrores([
            validarRequerido($usuario, 'Usuario'),
            validarRequerido($clave,   'Contrasena'),
        ]);

        // 3. Autenticar si no hay errores de formato
        if (empty($errores)) {
            $modelo   = new UsuarioModel();
            $registro = $modelo->buscarPorUsuario($usuario);

            if ($registro && password_verify($clave, $registro['clavehash'])) {
                iniciarSesionUsuario((int) $registro['id_usuario'], $registro['usuario']);
                header('Location: index.php');
                exit;
            } else {
                // Mensaje generico para no revelar si el usuario existe
                $errores[] = 'Usuario o contrasena incorrectos.';
            }
        }
    } catch (RuntimeException $e) {
        $errores[] = $e->getMessage();
    } catch (Exception $e) {
        $errores[] = 'Error interno. Por favor intente de nuevo.';
        error_log('[login.php] ' . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UTEDE - Inicio de Sesion</title>
</head>
<body>
    <?php require_once __DIR__ . '/forms/form_login.php'; ?>
</body>
</html>
