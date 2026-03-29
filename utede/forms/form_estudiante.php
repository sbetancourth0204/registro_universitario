<?php
/**
 * forms/form_estudiante.php
 * Formulario HTML para registrar un nuevo estudiante.
 * Se incluye desde registrar_estudiante.php; espera $errores y $datos.
 *
 * @var string[] $errores   Lista de mensajes de error.
 * @var array    $datos     Valores previos para repoblar el formulario.
 */
?>
<h1>Universidad UTEDE</h1>
<h2>Registrar Nuevo Estudiante</h2>

<?php if (!empty($errores)): ?>
    <ul>
        <?php foreach ($errores as $error): ?>
            <li><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<form method="POST" action="registrar_estudiante.php">
    <fieldset>
        <legend>Datos del estudiante</legend>

        <label for="identificacion">Identificacion:</label><br>
        <input
            type="text"
            id="identificacion"
            name="identificacion"
            maxlength="20"
            value="<?= htmlspecialchars($datos['identificacion'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
        ><br><br>

        <label for="apellidos">Apellidos:</label><br>
        <input
            type="text"
            id="apellidos"
            name="apellidos"
            maxlength="100"
            value="<?= htmlspecialchars($datos['apellidos'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
        ><br><br>

        <label for="nombre">Nombre:</label><br>
        <input
            type="text"
            id="nombre"
            name="nombre"
            maxlength="100"
            value="<?= htmlspecialchars($datos['nombre'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
        ><br><br>

        <label for="email">Email:</label><br>
        <input
            type="email"
            id="email"
            name="email"
            maxlength="150"
            value="<?= htmlspecialchars($datos['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
        ><br><br>

        <button type="submit">Registrar Estudiante</button>
    </fieldset>
</form>

<p><a href="index.php">Volver al listado</a></p>
