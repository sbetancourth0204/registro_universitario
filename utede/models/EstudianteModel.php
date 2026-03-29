<?php
/**
 * models/EstudianteModel.php
 * Operaciones PDO sobre la tabla «estudiantes».
 * Todas las consultas usan sentencias preparadas para prevenir inyección SQL.
 */

require_once __DIR__ . '/../config/database.php';

class EstudianteModel
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
     * Retorna todos los estudiantes ordenados por apellidos y nombre.
     *
     * @return array<int, array<string, mixed>>
     */
    public function listarTodos(): array
    {
        $stmt = $this->pdo->query(
            'SELECT id_estudiante, identificacion, apellidos, nombre, email
               FROM estudiantes
              ORDER BY apellidos, nombre'
        );
        return $stmt->fetchAll();
    }

    /**
     * Busca un estudiante por su id_estudiante.
     * Retorna el registro o false si no existe.
     */
    public function buscarPorId(int $id): array|false
    {
        $stmt = $this->pdo->prepare(
            'SELECT id_estudiante, identificacion, apellidos, nombre, email
               FROM estudiantes
              WHERE id_estudiante = :id
              LIMIT 1'
        );
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Verifica si una identificación ya está registrada.
     * Si se pasa $excluirId, ignora ese registro (útil en edición).
     */
    public function existeIdentificacion(string $identificacion, ?int $excluirId = null): bool
    {
        if ($excluirId !== null) {
            $stmt = $this->pdo->prepare(
                'SELECT COUNT(*) FROM estudiantes
                  WHERE identificacion = :identificacion
                    AND id_estudiante  != :id'
            );
            $stmt->execute([':identificacion' => $identificacion, ':id' => $excluirId]);
        } else {
            $stmt = $this->pdo->prepare(
                'SELECT COUNT(*) FROM estudiantes WHERE identificacion = :identificacion'
            );
            $stmt->execute([':identificacion' => $identificacion]);
        }
        return (bool) $stmt->fetchColumn();
    }

    /**
     * Verifica si un email ya está registrado.
     * Si se pasa $excluirId, ignora ese registro (útil en edición).
     */
    public function existeEmail(string $email, ?int $excluirId = null): bool
    {
        if ($excluirId !== null) {
            $stmt = $this->pdo->prepare(
                'SELECT COUNT(*) FROM estudiantes
                  WHERE email          = :email
                    AND id_estudiante != :id'
            );
            $stmt->execute([':email' => $email, ':id' => $excluirId]);
        } else {
            $stmt = $this->pdo->prepare(
                'SELECT COUNT(*) FROM estudiantes WHERE email = :email'
            );
            $stmt->execute([':email' => $email]);
        }
        return (bool) $stmt->fetchColumn();
    }

    // ----------------------------------------------------------------
    // Escritura
    // ----------------------------------------------------------------

    /**
     * Registra un nuevo estudiante.
     */
    public function registrar(
        string $identificacion,
        string $apellidos,
        string $nombre,
        string $email
    ): bool {
        $stmt = $this->pdo->prepare(
            'INSERT INTO estudiantes (identificacion, apellidos, nombre, email)
             VALUES (:identificacion, :apellidos, :nombre, :email)'
        );
        return $stmt->execute([
            ':identificacion' => $identificacion,
            ':apellidos'      => $apellidos,
            ':nombre'         => $nombre,
            ':email'          => $email,
        ]);
    }

    /**
     * Actualiza los datos de un estudiante existente.
     */
    public function actualizar(
        int    $id,
        string $identificacion,
        string $apellidos,
        string $nombre,
        string $email
    ): bool {
        $stmt = $this->pdo->prepare(
            'UPDATE estudiantes
                SET identificacion = :identificacion,
                    apellidos      = :apellidos,
                    nombre         = :nombre,
                    email          = :email
              WHERE id_estudiante  = :id'
        );
        return $stmt->execute([
            ':identificacion' => $identificacion,
            ':apellidos'      => $apellidos,
            ':nombre'         => $nombre,
            ':email'          => $email,
            ':id'             => $id,
        ]);
    }
}
