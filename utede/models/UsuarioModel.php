<?php
/**
 * models/UsuarioModel.php
 * Operaciones PDO sobre la tabla «usuarios».
 * Todas las consultas usan sentencias preparadas para prevenir inyección SQL.
 */

require_once __DIR__ . '/../config/database.php';

class UsuarioModel
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = obtenerConexion();
    }

    // ----------------------------------------------------------------
    // Consultas
    // ----------------------------------------------------------------

    /**
     * Busca un usuario por su nombre de usuario.
     * Retorna el registro o false si no existe.
     */
    public function buscarPorUsuario(string $usuario): array|false
    {
        $stmt = $this->pdo->prepare(
            'SELECT id_usuario, usuario, clavehash
               FROM usuarios
              WHERE usuario = :usuario
              LIMIT 1'
        );
        $stmt->execute([':usuario' => $usuario]);
        return $stmt->fetch();
    }

    /**
     * Comprueba si una identificación ya está registrada en usuarios.
     */
    public function existeIdentificacion(string $identificacion): bool
    {
        $stmt = $this->pdo->prepare(
            'SELECT COUNT(*) FROM usuarios WHERE identificacion = :identificacion'
        );
        $stmt->execute([':identificacion' => $identificacion]);
        return (bool) $stmt->fetchColumn();
    }

    /**
     * Comprueba si un nombre de usuario ya está en uso.
     */
    public function existeUsuario(string $usuario): bool
    {
        $stmt = $this->pdo->prepare(
            'SELECT COUNT(*) FROM usuarios WHERE usuario = :usuario'
        );
        $stmt->execute([':usuario' => $usuario]);
        return (bool) $stmt->fetchColumn();
    }

    // ----------------------------------------------------------------
    // Escritura
    // ----------------------------------------------------------------

    /**
     * Registra un nuevo usuario.
     * La contraseña se almacena como hash bcrypt (PASSWORD_BCRYPT).
     */
    public function registrar(
        string $identificacion,
        string $apellidos,
        string $nombres,
        string $usuario,
        string $claveTextoPlano
    ): bool {
        $clavehash = password_hash($claveTextoPlano, PASSWORD_BCRYPT, ['cost' => 12]);

        $stmt = $this->pdo->prepare(
            'INSERT INTO usuarios (identificacion, apellidos, nombres, usuario, clavehash)
             VALUES (:identificacion, :apellidos, :nombres, :usuario, :clavehash)'
        );
        return $stmt->execute([
            ':identificacion' => $identificacion,
            ':apellidos'      => $apellidos,
            ':nombres'        => $nombres,
            ':usuario'        => $usuario,
            ':clavehash'      => $clavehash,
        ]);
    }
}
