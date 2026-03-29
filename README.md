# Sistema CRUD UTEDE — Gestión de Estudiantes

Aplicación web desarrollada en PHP 8.x para la **Universidad UTEDE** que permite administrar el registro de estudiantes mediante un panel protegido por autenticación de usuarios.

---

## Tabla de contenido

- [Descripción general](#descripción-general)
- [Requisitos](#requisitos)
- [Estructura del proyecto](#estructura-del-proyecto)
- [Instalación](#instalación)
- [Uso de la aplicación](#uso-de-la-aplicación)
- [Seguridad implementada](#seguridad-implementada)
- [Diagramas C4](#diagramas-c4)
- [Decisiones arquitectónicas (ADR)](#decisiones-arquitectónicas-adr)

---

## Descripción general

El sistema provee las siguientes funcionalidades:

- Registro de usuarios con contraseña protegida por hash **bcrypt**.
- Inicio de sesión con validación de credenciales y gestión segura de sesión.
- Registro de nuevos estudiantes.
- Listado completo de estudiantes.
- Edición de datos de cualquier estudiante buscado por su ID.
- Cierre de sesión con destrucción completa de la sesión.

---

## Requisitos

| Componente | Versión mínima |
|---|---|
| PHP | 8.0 |
| MySQL / MariaDB | 5.7 / 10.3 |
| Servidor web | Apache 2.4 o Nginx 1.18 |
| Extensión PHP | PDO, PDO_MySQL, mbstring |

> El proyecto es compatible con entornos **XAMPP**, **WAMP** y **Laragon**.

---

## Estructura del proyecto

```
utede/
├── sql/
│   └── utede.sql                    # Script DDL: crea BD y tablas
├── config/
│   └── database.php                 # Conexión PDO singleton
├── includes/
│   ├── auth.php                     # Gestión de sesión y autenticación
│   ├── sanitizar.php                # Sanitización de entradas de formularios
│   └── validar.php                  # Validación de reglas de negocio
├── models/
│   ├── UsuarioModel.php             # Operaciones PDO — tabla usuarios
│   └── EstudianteModel.php          # Operaciones PDO — tabla estudiantes
├── forms/
│   ├── form_login.php               # Formulario HTML de inicio de sesión
│   ├── form_registro_usuario.php    # Formulario HTML de registro de usuario
│   ├── form_estudiante.php          # Formulario HTML de nuevo estudiante
│   └── form_editar_estudiante.php   # Formulario HTML de edición de estudiante
├── login.php                        # Controlador: inicio de sesión
├── registro_usuario.php             # Controlador: registro de usuario
├── index.php                        # Controlador: panel principal y listado
├── registrar_estudiante.php         # Controlador: alta de estudiante
├── editar_estudiante.php            # Controlador: búsqueda y edición
└── logout.php                       # Controlador: cierre de sesión
```

---

## Instalación

### Paso 1 — Crear la base de datos

**Opción A — phpMyAdmin:**

1. Abrir `http://localhost/phpmyadmin`.
2. Ir a la pestaña **SQL**.
3. Copiar el contenido de `sql/utede.sql` y hacer clic en **Ejecutar**.

**Opción B — Línea de comandos:**

```bash
mysql -u root -p < ruta/utede/sql/utede.sql
```

### Paso 2 — Configurar la conexión

Editar `config/database.php` con las credenciales del entorno:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'utede');
define('DB_USER', 'tu_usuario');   // Cambiar
define('DB_PASS', 'tu_clave');     // Cambiar
```

> **Importante:** No subir este archivo a repositorios públicos.  
> Se recomienda agregar `config/database.php` al `.gitignore`.

### Paso 3 — Desplegar los archivos

Copiar la carpeta `utede/` al directorio raíz del servidor web:

- XAMPP → `C:\xampp\htdocs\utede\`
- WAMP  → `C:\wamp64\www\utede\`
- Linux → `/var/www/html/utede/`

### Paso 4 — Acceder a la aplicación

Abrir en el navegador:

```
http://localhost/utede/login.php
```

Desde ahí se puede registrar un usuario nuevo o iniciar sesión con uno existente.

---

## Uso de la aplicación

### Registro de usuario

1. En la pantalla de login, hacer clic en **"No tienes cuenta? Regístrate aquí"**.
2. Completar los campos: Identificación, Apellidos, Nombres, Usuario y Contraseña.
3. La contraseña debe tener mínimo 8 caracteres, una mayúscula, una minúscula y un número.
4. Al registrarse exitosamente, se redirige al login.

### Inicio de sesión

1. Ingresar el nombre de usuario y la contraseña en el formulario de login.
2. Al autenticarse, se redirige al panel principal.

### Panel principal

Desde `index.php` se puede:

- Ver el **listado completo** de estudiantes registrados.
- Acceder al formulario de **registro de nuevo estudiante**.
- **Buscar un estudiante por su ID** para editar su información.
- **Cerrar sesión**.

### Registro de estudiante

Completar los campos: Identificación, Apellidos, Nombre y Email.  
La identificación y el email deben ser únicos en el sistema.

### Edición de estudiante

1. En el panel principal, ingresar el ID del estudiante en el campo de búsqueda.
2. Se carga el formulario con los datos actuales del estudiante.
3. Modificar los campos necesarios y hacer clic en **Actualizar Estudiante**.

---

## Seguridad implementada

| Mecanismo | Implementación |
|---|---|
| **Prevención de SQL Injection** | PDO con sentencias preparadas (`prepare` + `execute`) en todos los modelos. `EMULATE_PREPARES = false` activa sentencias reales en MySQL. |
| **Sanitización de entradas** | `sanitizarTexto()`, `sanitizarEmail()` y `sanitizarEntero()` se aplican antes de cualquier procesamiento. |
| **Validación de formularios** | Funciones en `validar.php`: campo requerido, longitud, solo texto, formato email, alfanumérico y fortaleza de contraseña. |
| **Hash de contraseñas** | `password_hash($clave, PASSWORD_BCRYPT, ['cost' => 12])` al registrar. `password_verify()` al autenticar. |
| **Protección XSS** | Toda salida HTML impresa con `htmlspecialchars(ENT_QUOTES, 'UTF-8')`. IDs numéricos casteados a `(int)`. |
| **Session Fixation** | `session_regenerate_id(true)` al iniciar cada sesión autenticada. |
| **Logout seguro** | `cerrarSesion()` limpia `$_SESSION`, destruye la cookie de sesión y llama `session_destroy()`. |
| **Manejo de excepciones** | `try/catch` en todos los controladores. Errores internos van a `error_log()`, nunca al usuario. |
| **Validación de integridad en edición** | Se verifica que el `id_estudiante` del formulario coincida con el `id` de la URL antes de actualizar. |
| **Unicidad con exclusión** | Al editar, los métodos `existeIdentificacion()` y `existeEmail()` excluyen el propio registro para evitar falsos positivos. |

### Mejoras de seguridad recomendadas para producción

- Implementar **tokens CSRF** en todos los formularios POST.
- Agregar **límite de intentos** de inicio de sesión por IP o usuario.
- Mover las credenciales de BD a **variables de entorno** (`.env`).
- Emitir **cabeceras de seguridad HTTP** (`X-Frame-Options`, `Content-Security-Policy`, etc.).
- Proteger los directorios internos con `.htaccess`: `Deny from all`.
- Migrar los mensajes de retroalimentación al patrón **PRG con flash de sesión**.

---

## Diagramas C4

Los diagramas de arquitectura se encuentran en la carpeta `docs/` en formato PlantUML (`.puml`).

| Archivo | Nivel | Contenido |
|---|---|---|
| `c4_nivel1_contexto.puml` | Contexto | Sistema, usuario y sistemas externos |
| `c4_nivel2_contenedor.puml` | Contenedor | App Web PHP, MySQL y almacén de sesiones |
| `c4_nivel3_componente.puml` | Componente | Todos los archivos PHP y sus relaciones |
| `c4_nivel4_codigo.puml` | Código | Flujo detallado de edición de estudiante |

Para renderizarlos, pegar el contenido en [https://www.plantuml.com/plantuml/uml](https://www.plantuml.com/plantuml/uml) o usar la extensión **PlantUML** en VS Code.

---

## Decisiones arquitectónicas (ADR)

Las decisiones de diseño más importantes documentadas:

| ADR | Decisión |
|---|---|
| ADR-001 | Arquitectura MVC procedimental sin framework |
| ADR-002 | PDO con sentencias preparadas como capa de acceso a datos |
| ADR-003 | Hashing bcrypt con `cost = 12` para contraseñas |
| ADR-004 | Separación de sanitización, validación y formularios en archivos independientes |
| ADR-005 | Gestión de sesión con `$_SESSION` nativo de PHP |
| ADR-006 | Base de datos MySQL con colación `utf8mb4_unicode_ci` |
| ADR-007 | Mensajes de retroalimentación vía parámetro GET (pendiente migración a flash) |

El detalle completo se encuentra en `docs/adr.txt`.

---

## Esquema de base de datos

```sql
-- Tabla de usuarios del sistema
CREATE TABLE usuarios (
    id_usuario      INT          NOT NULL AUTO_INCREMENT,
    identificacion  VARCHAR(20)  NOT NULL UNIQUE,
    apellidos       VARCHAR(100) NOT NULL,
    nombres         VARCHAR(100) NOT NULL,
    usuario         VARCHAR(50)  NOT NULL UNIQUE,
    clavehash       VARCHAR(255) NOT NULL,
    PRIMARY KEY (id_usuario)
);

-- Tabla de estudiantes
CREATE TABLE estudiantes (
    id_estudiante   INT          NOT NULL AUTO_INCREMENT,
    identificacion  VARCHAR(20)  NOT NULL UNIQUE,
    apellidos       VARCHAR(100) NOT NULL,
    nombre          VARCHAR(100) NOT NULL,
    email           VARCHAR(150) NOT NULL UNIQUE,
    PRIMARY KEY (id_estudiante)
);
```

---

*Universidad UTEDE — Sistema de Gestión de Estudiantes*
