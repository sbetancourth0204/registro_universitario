<?php
/**
 * editar_estudiante.php
 * Controlador para buscar y editar un estudiante por su id_estudiante.
 * Requiere sesion activa.
 */

session_start();

require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/sanitizar.php';
require_once __DIR__ . '/includes/validar.php';
require_once __DIR__ . '/models/EstudianteModel.php';

verificarSesion();

$errores    = [];
$datos      = [];
$estudiante = null;
$modelo     = new EstudianteModel();

// ----------------------------------------------------------------
// Obtener y validar el ID recibido por GET
// ----------------------------------------------------------------
$id = sanitizarEntero($_GET['id'] ?? 0);

if ($id <= 0) {
    // Mostrar formulario de busqueda si no hay ID valido
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>UTEDE - Buscar Estudiante</title>
    </head>
    <body>
        <h1>Universidad UTEDE</h1>
        <h2>Buscar Estudiante por ID</h2>
        <form method="GET" action="editar_estudiante.php">
            <label for="id">ID del estudiante:</label>
            <input type="number" id="id" name="id" min="1">
            <button type="submit">Buscar</button>
        </form>
        <p><a href="index.php">Volver al listado</a></p>
    </body>
    </html>
    <?php
    exit;
}

// ----------------------------------------------------------------
// Buscar el estudiante en la BD
// ----------------------------------------------------------------
try {
    $estudiante = $modelo->buscarPorId($id);
} catch (RuntimeException $e) {
    ?>
    <!DOCTYPE html>
    <html lang="es"><head><meta charset="UTF-8"><title>UTEDE</title></head>
    <body>
        <p><?= htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') ?></p>
        <p><a href="index.php">Volver al listado</a></p>
    </body></html>
    <?php
    exit;
} catch (Exception $e) {
    error_log('[editar_estudiante.php] ' . $e->getMessage());
    ?>
    <!DOCTYPE html>
    <html lang="es"><head><meta charset="UTF-8"><title>UTEDE</title></head>
    <body>
        <p>Error al buscar el estudiante. Intente mas tarde.</p>
        <p><a href="index.php">Volver al listado</a></p>
    </body></html>
    <?php
    exit;
}

if (!$estudiante) {
    ?>
    <!DOCTYPE html>
    <html lang="es"><head><meta charset="UTF-8"><title>UTEDE</title></head>
    <body>
        <p>No se encontro ningun estudiante con el ID <?= $id ?>.</p>
        <p><a href="index.php">Volver al listado</a></p>
    </body></html>
    <?php
    exit;
}

// ----------------------------------------------------------------
// Procesar actualizacion
// ----------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // 1. Sanitizar
        $datos = [
            'identificacion' => sanitizarTexto($_POST['identificacion'] ?? ''),
            'apellidos'      => sanitizarTexto($_POST['apellidos']      ?? ''),
            'nombre'         => sanitizarTexto($_POST['nombre']         ?? ''),
            'email'          => sanitizarEmail($_POST['email']          ?? ''),
        ];
        $idPost = sanitizarEntero($_POST['id_estudiante'] ?? 0);

        // Verificar que el ID del formulario coincida con el de la URL
        if ($idPost !== $id) {
            $errores[] = 'Error de integridad del formulario. Recargue la pagina.';
        }

        // 2. Validar
        if (empty($errores)) {
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
        }

        // 3. Verificar unicidad excluyendo el registro actual
        if (empty($errores)) {
            if ($modelo->existeIdentificacion($datos['identificacion'], $id)) {
                $errores[] = 'La identificacion ya esta registrada para otro estudiante.';
            }
            if ($modelo->existeEmail($datos['email'], $id)) {
                $errores[] = 'El email ya esta registrado para otro estudiante.';
            }
        }

        // 4. Actualizar
        if (empty($errores)) {
            $modelo->actualizar(
                $id,
                $datos['identificacion'],
                $datos['apellidos'],
                $datos['nombre'],
                $datos['email']
            );
            header('Location: index.php?mensaje=actualizado');
            exit;
        }
    } catch (RuntimeException $e) {
        $errores[] = $e->getMessage();
    } catch (Exception $e) {
        $errores[] = 'Error interno. Por favor intente de nuevo.';
        error_log('[editar_estudiante.php POST] ' . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UTEDE - Editar Estudiante</title>
</head>
<body>
    <?php require_once __DIR__ . '/forms/form_editar_estudiante.php'; ?>
</body>
</html>
