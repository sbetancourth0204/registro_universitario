<?php
/**
 * forms/form_login.php
 * Formulario HTML de inicio de sesión.
 * Se incluye desde login.php; espera las variables $errores y $usuario.
 *
 * @var string[] $errores   Lista de mensajes de error.
 * @var string   $usuario   Valor previo del campo usuario (para repoblar).
 */
?>
<h1>Universidad UTEDE</h1>
<h2>Inicio de Sesion</h2>

<?php if (!empty($errores)): ?>
    <ul>
        <?php foreach ($errores as $error): ?>
            <li><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<form method="POST" action="login.php">
    <fieldset>
        <legend>Credenciales</legend>

        <label for="usuario">Usuario:</label><br>
        <input
            type="text"
            id="usuario"
            name="usuario"
            maxlength="50"
            autocomplete="username"
            value="<?= htmlspecialchars($usuario ?? '', ENT_QUOTES, 'UTF-8') ?>"
        ><br><br>

        <label for="clave">Contrasena:</label><br>
        <input
            type="password"
            id="clave"
            name="clave"
            maxlength="100"
            autocomplete="current-password"
        ><br><br>

        <button type="submit">Ingresar</button>
    </fieldset>
</form>

<p><a href="registro_usuario.php">No tienes cuenta? Registrate aqui</a></p>
