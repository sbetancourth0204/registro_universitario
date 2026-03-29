<?php
/**
 * index.php
 * Panel principal: listado completo de estudiantes y enlace de busqueda.
 * Requiere sesion activa.
 */

session_start();

require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/models/EstudianteModel.php';

verificarSesion();

$errores     = [];
$estudiantes = [];

// Mensaje de retroalimentacion tras operaciones exitosas
$mensaje = htmlspecialchars($_GET['mensaje'] ?? '', ENT_QUOTES, 'UTF-8');

try {
    $modelo      = new EstudianteModel();
    $estudiantes = $modelo->listarTodos();
} catch (RuntimeException $e) {
    $errores[] = $e->getMessage();
} catch (Exception $e) {
    $errores[] = 'Error al obtener el listado de estudiantes.';
    error_log('[index.php] ' . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UTEDE - Panel de Estudiantes</title>
</head>
<body>
    <h1>Universidad UTEDE - Gestion de Estudiantes</h1>

    <p>
        Bienvenido,
        <strong><?= htmlspecialchars($_SESSION['usuario'], ENT_QUOTES, 'UTF-8') ?></strong>
        &nbsp;|&nbsp;
        <a href="logout.php">Cerrar sesion</a>
    </p>

    <hr>

    <!-- Mensajes de exito -->
    <?php if ($mensaje === 'registrado'): ?>
        <p>Estudiante registrado exitosamente.</p>
    <?php elseif ($mensaje === 'actualizado'): ?>
        <p>Informacion del estudiante actualizada exitosamente.</p>
    <?php endif; ?>

    <!-- Errores -->
    <?php if (!empty($errores)): ?>
        <ul>
            <?php foreach ($errores as $error): ?>
                <li><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <p><a href="registrar_estudiante.php">Registrar nuevo estudiante</a></p>

    <hr>

    <!-- Buscar estudiante para editar -->
    <h2>Buscar Estudiante por ID para Editar</h2>
    <form method="GET" action="editar_estudiante.php">
        <label for="id_buscar">ID del estudiante:</label>
        <input type="number" id="id_buscar" name="id" min="1">
        <button type="submit">Buscar</button>
    </form>

    <hr>

    <!-- Listado completo -->
    <h2>Listado de Estudiantes</h2>

    <?php if (empty($estudiantes)): ?>
        <p>No hay estudiantes registrados aun.</p>
    <?php else: ?>
        <table border="1" cellpadding="5" cellspacing="0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Identificacion</th>
                    <th>Apellidos</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($estudiantes as $est): ?>
                <tr>
                    <td><?= (int) $est['id_estudiante'] ?></td>
                    <td><?= htmlspecialchars($est['identificacion'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($est['apellidos'],      ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($est['nombre'],         ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($est['email'],          ENT_QUOTES, 'UTF-8') ?></td>
                    <td>
                        <a href="editar_estudiante.php?id=<?= (int) $est['id_estudiante'] ?>">Editar</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>
