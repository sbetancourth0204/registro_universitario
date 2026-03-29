<?php
/**
 * registro_usuario.php
 * Controlador de registro de nuevos usuarios del sistema.
 * Sanitiza, valida, verifica unicidad y almacena con clave hasheada.
 */

session_start();

require_once __DIR__ . '/includes/sanitizar.php';
require_once __DIR__ . '/includes/validar.php';
require_once __DIR__ . '/models/UsuarioModel.php';

// Si ya hay sesion activa, redirigir al panel principal
if (!empty($_SESSION['id_usuario'])) {
    header('Location: index.php');
    exit;
}

$errores = [];
$datos   = [];
$exito   = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // 1. Sanitizar entradas
        $datos = [
            'identificacion' => sanitizarTexto($_POST['identificacion'] ?? ''),
            'apellidos'      => sanitizarTexto($_POST['apellidos']      ?? ''),
            'nombres'        => sanitizarTexto($_POST['nombres']        ?? ''),
            'usuario'        => sanitizarTexto($_POST['usuario']        ?? ''),
        ];
        $clave          = $_POST['clave']          ?? '';
        $claveConfirmar = $_POST['clave_confirmar'] ?? '';

        // 2. Validar campos
        $errores = recopilarErrores([
            validarRequerido($datos['identificacion'], 'Identificacion'),
            validarLongitud($datos['identificacion'],  'Identificacion', 3, 20),
            validarRequerido($datos['apellidos'], 'Apellidos'),
            validarSoloTexto($datos['apellidos'], 'Apellidos'),
            validarRequerido($datos['nombres'], 'Nombres'),
            validarSoloTexto($datos['nombres'], 'Nombres'),
            validarRequerido($datos['usuario'],    'Usuario'),
            validarLongitud($datos['usuario'],     'Usuario', 4, 50),
            validarAlfanumerico($datos['usuario'], 'Usuario'),
            validarRequerido($clave, 'Contrasena'),
            validarContrasena($clave),
        ]);

        if ($clave !== $claveConfirmar) {
            $errores[] = 'Las contrasenas no coinciden.';
        }

        // 3. Verificar unicidad en BD (solo si no hay errores previos)
        if (empty($errores)) {
            $modelo = new UsuarioModel();

            if ($modelo->existeIdentificacion($datos['identificacion'])) {
                $errores[] = 'La identificacion ya esta registrada.';
            }
            if ($modelo->existeUsuario($datos['usuario'])) {
                $errores[] = 'El nombre de usuario ya esta en uso.';
            }
        }

        // 4. Registrar si todo es valido
        if (empty($errores)) {
            $modelo->registrar(
                $datos['identificacion'],
                $datos['apellidos'],
                $datos['nombres'],
                $datos['usuario'],
                $clave
            );
            $exito = true;
            $datos = [];
        }
    } catch (RuntimeException $e) {
        $errores[] = $e->getMessage();
    } catch (Exception $e) {
        $errores[] = 'Error interno. Por favor intente de nuevo.';
        error_log('[registro_usuario.php] ' . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UTEDE - Registro de Usuario</title>
</head>
<body>
<?php if ($exito): ?>
    <h1>Universidad UTEDE</h1>
    <p>Usuario registrado exitosamente.</p>
    <p><a href="login.php">Ir al inicio de sesion</a></p>
<?php else: ?>
    <?php require_once __DIR__ . '/forms/form_registro_usuario.php'; ?>
<?php endif; ?>
</body>
</html>
