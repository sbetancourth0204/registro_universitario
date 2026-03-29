<?php
/**
 * logout.php
 * Cierra la sesion del usuario y redirige al login.
 */

session_start();

require_once __DIR__ . '/includes/auth.php';

cerrarSesion();

header('Location: login.php');
exit;
