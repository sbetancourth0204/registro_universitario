<?php
/**
 * includes/sanitizar.php
 * Funciones de sanitización de datos provenientes de formularios.
 * Se aplican ANTES de cualquier validación o almacenamiento.
 */

/**
 * Elimina etiquetas HTML/PHP, espacios extremos y codifica caracteres
 * especiales para evitar XSS.
 */
function sanitizarTexto(string $valor): string
{
    return htmlspecialchars(
        strip_tags(trim($valor)),
        ENT_QUOTES | ENT_SUBSTITUTE,
        'UTF-8'
    );
}

/**
 * Sanitiza una dirección de correo electrónico.
 * Elimina caracteres no permitidos según RFC.
 */
function sanitizarEmail(string $email): string
{
    $email = trim($email);
    return filter_var($email, FILTER_SANITIZE_EMAIL) ?: '';
}

/**
 * Sanitiza y convierte un valor a entero seguro.
 * Útil para IDs recibidos por GET/POST.
 */
function sanitizarEntero(mixed $valor): int
{
    return (int) filter_var($valor, FILTER_SANITIZE_NUMBER_INT);
}
