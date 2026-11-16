# Trabajo Práctico 10: gestión de jugadores de Esports

Aplicación web individual para administrar jugadores de Esports y su relación con equipos. El proyecto continúa la base de datos trabajada en los prácticos anteriores y permite consultar, buscar, crear, editar y eliminar jugadores.

Repositorio público: https://github.com/tomascacungalarza-tech/tp10-esports

## Requisitos

- XAMPP con Apache y MySQL.
- PHP 8 o posterior, con PDO MySQL habilitado.
- Navegador web actualizado.

## Instalación en XAMPP

1. Copiar los archivos del repositorio en `C:\xampp\htdocs\tp10`.
2. Iniciar Apache y MySQL desde el panel de XAMPP.
3. Importar `script_BD.sql` desde phpMyAdmin.
4. Copiar `conexion.php.example` como `config.php` y completar los datos de la base local. El archivo `config.php` está excluido de Git.
5. Abrir `http://localhost/tp10/`.

## Funciones

- Listado de jugadores y equipo asociado.
- Búsqueda por nickname, nombre, apellido, posición, rol o equipo.
- Alta, edición y eliminación de jugadores.
- Selección del equipo desde la tabla relacionada.
- Validación de datos en el servidor.

## Estructura de datos

La aplicación opera sobre `jugador` y consulta `equipo` para mostrar y seleccionar la relación entre ambas tablas. El script `script_BD.sql` contiene el esquema de Esports utilizado por el proyecto.

## Seguridad

Las consultas usan PDO con parámetros, las salidas HTML se escapan y las acciones de escritura validan un token CSRF. No subir credenciales reales a GitHub. Si una clave fue publicada anteriormente, cambiarla en el proveedor de base de datos.

## Evidencias de funcionamiento

La consigna solicita capturas de la interfaz en ejecución. Guardarlas en `capturas/` después de probar la aplicación localmente: listado, búsqueda, formulario de alta o edición y resultado de una operación CRUD.
