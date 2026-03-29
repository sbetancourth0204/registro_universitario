<?php
/**
 * forms/form_registro_usuario.php
 * Formulario HTML de registro de usuario.
 * Se incluye desde registro_usuario.php; espera $errores y $datos.
 *
 * @var string[] $errores   Lista de mensajes de error.
 * @var array    $datos     Valores previos para repoblar el formulario.
 */
?>
<h1>Universidad UTEDE</h1>
<h2>Registro de Usuario</h2>

<?php if (!empty($errores)): ?>
    <ul>
        <?php foreach ($errores as $error): ?>
            <li><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<form method="POST" action="registro_usuario.php">
    <fieldset>
        <legend>Datos personales</legend>

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

        <label for="nombres">Nombres:</label><br>
        <input
            type="text"
            id="nombres"
            name="nombres"
            maxlength="100"
            value="<?= htmlspecialchars($datos['nombres'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
        ><br><br>
    </fieldset>

    <fieldset>
        <legend>Credenciales de acceso</legend>

        <label for="usuario">Usuario (solo letras, numeros y _):</label><br>
        <input
            type="text"
            id="usuario"
            name="usuario"
            maxlength="50"
            autocomplete="username"
            value="<?= htmlspecialchars($datos['usuario'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
        ><br><br>

        <label for="clave">Contrasena (min. 8 caracteres, mayusc., minusc. y numero):</label><br>
        <input
            type="password"
            id="clave"
            name="clave"
            maxlength="100"
            autocomplete="new-password"
        ><br><br>

        <label for="clave_confirmar">Confirmar contrasena:</label><br>
        <input
            type="password"
            id="clave_confirmar"
            name="clave_confirmar"
            maxlength="100"
            autocomplete="new-password"
        ><br><br>

        <button type="submit">Registrarse</button>
    </fieldset>
</form>

<p><a href="login.php">Ya tienes cuenta? Inicia sesion</a></p>
