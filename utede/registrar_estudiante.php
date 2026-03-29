<?php
/**
 * registrar_estudiante.php
 * Controlador para registrar un nuevo estudiante.
 * Requiere sesion activa.
 */

session_start();

require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/sanitizar.php';
require_once __DIR__ . '/includes/validar.php';
require_once __DIR__ . '/models/EstudianteModel.php';

verificarSesion();

$errores = [];
$datos   = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // 1. Sanitizar entradas
        $datos = [
            'identificacion' => sanitizarTexto($_POST['identificacion'] ?? ''),
            'apellidos'      => sanitizarTexto($_POST['apellidos']      ?? ''),
            'nombre'         => sanitizarTexto($_POST['nombre']         ?? ''),
            'email'          => sanitizarEmail($_POST['email']          ?? ''),
        ];

        // 2. Validar
        $errores = recopilarErrores([
            validarRequerido($datos['identificacion'], 'Identificacion'),
            validarLongitud($datos['identificacion'],  'Identificacion', 3, 20),
            validarRequerido($datos['apellidos'], 'Apellidos'),
            validarSoloTexto($datos['apellidos'], 'Apellidos'),
            validarRequerido($datos['nombre'], 'Nombre'),
            validarSoloTexto($datos['nombre'], 'Nombre'),
            validarRequerido($datos['email'], 'Email'),
            validarEmail($datos['email']),
        ]);

        // 3. Verificar unicidad
        if (empty($errores)) {
            $modelo = new EstudianteModel();

            if ($modelo->existeIdentificacion($datos['identificacion'])) {
                $errores[] = 'La identificacion ya esta registrada para otro estudiante.';
            }
            if ($modelo->existeEmail($datos['email'])) {
                $errores[] = 'El email ya esta registrado para otro estudiante.';
            }
        }

        // 4. Registrar
        if (empty($errores)) {
            $modelo->registrar(
                $datos['identificacion'],
                $datos['apellidos'],
                $datos['nombre'],
                $datos['email']
            );
            header('Location: index.php?mensaje=registrado');
            exit;
        }
    } catch (RuntimeException $e) {
        $errores[] = $e->getMessage();
    } catch (Exception $e) {
        $errores[] = 'Error interno. Por favor intente de nuevo.';
        error_log('[registrar_estudiante.php] ' . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UTEDE - Registrar Estudiante</title>
</head>
<body>
    <?php require_once __DIR__ . '/forms/form_estudiante.php'; ?>
</body>
</html>
