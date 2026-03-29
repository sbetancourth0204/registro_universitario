<?php
/**
 * forms/form_editar_estudiante.php
 * Formulario HTML para editar los datos de un estudiante.
 * Se incluye desde editar_estudiante.php; espera $errores, $datos y $estudiante.
 *
 * @var string[] $errores     Lista de mensajes de error.
 * @var array    $datos       Datos enviados (para repoblar en caso de error).
 * @var array    $estudiante  Datos actuales del estudiante en BD.
 */

// En caso de error se muestran los datos enviados; en carga inicial, los de BD.
$val = static function (string $campo) use ($datos, $estudiante): string {
    $valor = array_key_exists($campo, $datos) ? $datos[$campo] : ($estudiante[$campo] ?? '');
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
};
?>
<h1>Universidad UTEDE</h1>
<h2>Editar Estudiante (ID: <?= (int) $estudiante['id_estudiante'] ?>)</h2>

<?php if (!empty($errores)): ?>
    <ul>
        <?php foreach ($errores as $error): ?>
            <li><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<form method="POST" action="editar_estudiante.php">
    <!-- ID oculto para identificar el registro a actualizar -->
    <input
        type="hidden"
        name="id_estudiante"
        value="<?= (int) $estudiante['id_estudiante'] ?>"
    >

    <fieldset>
        <legend>Datos del estudiante</legend>

        <label for="identificacion">Identificacion:</label><br>
        <input
            type="text"
            id="identificacion"
            name="identificacion"
            maxlength="20"
            value="<?= $val('identificacion') ?>"
        ><br><br>

        <label for="apellidos">Apellidos:</label><br>
        <input
            type="text"
            id="apellidos"
            name="apellidos"
            maxlength="100"
            value="<?= $val('apellidos') ?>"
        ><br><br>

        <label for="nombre">Nombre:</label><br>
        <input
            type="text"
            id="nombre"
            name="nombre"
            maxlength="100"
            value="<?= $val('nombre') ?>"
        ><br><br>

        <label for="email">Email:</label><br>
        <input
            type="email"
            id="email"
            name="email"
            maxlength="150"
            value="<?= $val('email') ?>"
        ><br><br>

        <button type="submit">Actualizar Estudiante</button>
    </fieldset>
</form>

<p><a href="index.php">Volver al listado</a></p>
