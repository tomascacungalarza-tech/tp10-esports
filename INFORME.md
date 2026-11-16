# Trabajo Práctico 10: aplicación web CRUD

## Sistema de gestión de jugadores de Esports

### Introducción

Este trabajo desarrolla una aplicación web para administrar jugadores de equipos de Esports. Permite consultar el plantel, buscar registros, incorporar jugadores, modificar sus datos y eliminarlos. Cada jugador puede vincularse con un equipo existente.

La propuesta retoma la base de datos de Esports trabajada en los prácticos anteriores y adapta a ese dominio el enfoque de gestión por formularios del material de apoyo de farmacia.

### Objetivo

Construir una interfaz con PHP, HTML, Bootstrap y MySQL para realizar operaciones CRUD sobre `jugador` y consultar la información relacionada de `equipo`.

### Modelo de datos

La tabla `equipo` identifica cada equipo mediante `id_equipo` y almacena su nombre, propietario, cantidad de integrantes, fecha de fundación, ranking y entrenador asociado. La tabla `jugador` conserva su identificador, nickname, nombres, apellidos, posición, rol, nacionalidad, fecha de nacimiento e identificador de equipo. El esquema también contempla contratos, torneos, participaciones, partidas, estadísticas, sponsors e historial.

La relación principal de la aplicación es uno a muchos entre equipos y jugadores. La clave foránea `jugador.id_equipo` permite asociar un jugador con un equipo y admite el valor nulo previsto por el esquema.

### Funcionamiento

`index.php` presenta el listado de jugadores junto con el nombre del equipo mediante una unión externa. El buscador filtra por nickname, nombres, apellidos, rol, posición o equipo. El formulario permite crear un jugador y reutiliza los campos para editar un registro.

`guardar.php` valida los datos recibidos y decide entre insertar o actualizar según el identificador. `eliminar.php` procesa la baja mediante una solicitud POST. Las escrituras validan un token CSRF. PDO ejecuta consultas parametrizadas y las salidas HTML se escapan antes de mostrarse.

### Puesta en marcha

Copiar el repositorio en `C:\xampp\htdocs\tp10`, iniciar Apache y MySQL desde el panel de XAMPP e importar `script_BD.sql` mediante phpMyAdmin. Luego, copiar `conexion.php.example` como `config.php`, completar la conexión local y abrir `http://localhost/tp10/`.

Las credenciales reales no deben incluirse en el repositorio público. Si alguna clave se publicó anteriormente, debe cambiarse en el proveedor correspondiente.

### Evidencias

La consigna solicita capturas de la aplicación funcionando: listado, búsqueda, formulario de alta o edición y resultado de una operación CRUD. Deben guardarse en `capturas/` una vez ejecutada la aplicación localmente.

### Referencias

- Consigna de Base de Datos 1, TP10, 2026.
- [Repositorio del TP9](https://github.com/tomascacungalarza-tech/tp9bdd).
- [Gestión de una farmacia, La Web del Programador](https://www.lawebdelprogramador.com/codigo/PHP/5296-Gestion-de-una-farmacia.html).
- [Bootstrap 5.3, guía de inicio](https://getbootstrap.com/docs/5.3/getting-started/introduction/).
- [PHP Manual, PDO::prepare](https://www.php.net/manual/en/pdo.prepare.php).