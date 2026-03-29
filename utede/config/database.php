<?php
/**
 * config/database.php
 * Configuración y conexión PDO a la base de datos UTEDE.
 * Protección contra inyección SQL: se usa PDO con sentencias preparadas.
 */

define('DB_HOST',    'localhost');
define('DB_NAME',    'utede');
define('DB_USER',    'root');       // Cambiar según entorno
define('DB_PASS',    '');           // Cambiar según entorno
define('DB_CHARSET', 'utf8mb4');

/**
 * Retorna una instancia singleton de PDO.
 *
 * @throws RuntimeException si la conexión falla.
 */
function obtenerConexion(): PDO
{
    static $pdo = null;

    if ($pdo === null) {
        $dsn = sprintf(
            'mysql:host=%s;dbname=%s;charset=%s',
            DB_HOST,
            DB_NAME,
            DB_CHARSET
        );

        $opciones = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,   // Sentencias reales preparadas
        ];

        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $opciones);
        } catch (PDOException $e) {
            // No exponer detalles al usuario; registrar internamente
            error_log('PDOException: ' . $e->getMessage());
            throw new RuntimeException('No se pudo conectar a la base de datos. Intente más tarde.');
        }
    }

    return $pdo;
}
