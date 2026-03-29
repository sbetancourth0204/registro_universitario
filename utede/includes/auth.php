<?php
/**
 * includes/auth.php
 * Funciones auxiliares de autenticación y manejo de sesión.
 */

/**
 * Verifica que el usuario tenga sesión activa.
 * Si no, redirige al login y termina la ejecución.
 */
function verificarSesion(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (empty($_SESSION['id_usuario'])) {
        header('Location: login.php');
        exit;
    }
}

/**
 * Inicia la sesión del usuario autenticado.
 * Regenera el ID de sesión para prevenir session fixation.
 */
function iniciarSesionUsuario(int $idUsuario, string $usuario): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    session_regenerate_id(true);
    $_SESSION['id_usuario'] = $idUsuario;
    $_SESSION['usuario']    = $usuario;
}

/**
 * Destruye completamente la sesión activa.
 */
function cerrarSesion(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params['path'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        );
    }
    session_destroy();
}
