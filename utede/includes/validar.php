<?php
/**
 * includes/validar.php
 * Funciones de validación de datos de formularios.
 * Cada función retorna un mensaje de error (string) o null si es válido.
 */

/**
 * Verifica que el campo no esté vacío.
 */
function validarRequerido(string $valor, string $etiqueta): ?string
{
    if (trim($valor) === '') {
        return "El campo «{$etiqueta}» es obligatorio.";
    }
    return null;
}

/**
 * Verifica que el campo tenga entre $min y $max caracteres.
 */
function validarLongitud(string $valor, string $etiqueta, int $min, int $max): ?string
{
    $longitud = mb_strlen(trim($valor), 'UTF-8');
    if ($longitud < $min || $longitud > $max) {
        return "El campo «{$etiqueta}» debe tener entre {$min} y {$max} caracteres.";
    }
    return null;
}

/**
 * Verifica que el campo contenga solo letras (incluyendo acentuadas) y espacios.
 * Adecuado para nombres y apellidos.
 */
function validarSoloTexto(string $valor, string $etiqueta): ?string
{
    if (!preg_match('/^[\p{L}\s\-\']+$/u', trim($valor))) {
        return "El campo «{$etiqueta}» solo puede contener letras, espacios y guiones.";
    }
    return null;
}

/**
 * Verifica que el campo sea un email válido según RFC 5321.
 */
function validarEmail(string $email): ?string
{
    if (!filter_var(trim($email), FILTER_VALIDATE_EMAIL)) {
        return 'El correo electrónico no tiene un formato válido.';
    }
    return null;
}

/**
 * Verifica que el campo contenga solo letras, números y guiones bajos.
 * Adecuado para nombres de usuario.
 */
function validarAlfanumerico(string $valor, string $etiqueta): ?string
{
    if (!preg_match('/^[a-zA-Z0-9_]+$/', trim($valor))) {
        return "El campo «{$etiqueta}» solo puede contener letras, números y guiones bajos.";
    }
    return null;
}

/**
 * Valida la fortaleza de la contraseña:
 * mínimo 8 caracteres, al menos una mayúscula, una minúscula y un dígito.
 */
function validarContrasena(string $clave): ?string
{
    if (mb_strlen($clave) < 8) {
        return 'La contraseña debe tener al menos 8 caracteres.';
    }
    if (!preg_match('/[A-Z]/', $clave)) {
        return 'La contraseña debe contener al menos una letra mayúscula.';
    }
    if (!preg_match('/[a-z]/', $clave)) {
        return 'La contraseña debe contener al menos una letra minúscula.';
    }
    if (!preg_match('/[0-9]/', $clave)) {
        return 'La contraseña debe contener al menos un número.';
    }
    return null;
}

/**
 * Recopila todos los errores de un conjunto de validaciones.
 * Recibe un array de resultados de funciones validar* y filtra los null.
 *
 * @param array<string|null> $resultados
 * @return string[]
 */
function recopilarErrores(array $resultados): array
{
    return array_values(array_filter($resultados));
}
