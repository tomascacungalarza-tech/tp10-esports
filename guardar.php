<?php
declare(strict_types=1);

require __DIR__ . '/conexion.php';
require __DIR__ . '/funciones.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Método no permitido.');
}
validarCsrf();

$idRecibido = trim((string) ($_POST['id_jugador'] ?? ''));
$id = $idRecibido === '' ? null : filter_var($idRecibido, FILTER_VALIDATE_INT);
$nickname = trim((string) ($_POST['nickname'] ?? ''));
$nombres = trim((string) ($_POST['nombres'] ?? ''));
$apellidos = trim((string) ($_POST['apellidos'] ?? ''));
$posicion = trim((string) ($_POST['posicion'] ?? ''));
$rol = trim((string) ($_POST['rol'] ?? ''));
$nacionalidad = trim((string) ($_POST['nacionalidad'] ?? ''));
$equipoRecibido = trim((string) ($_POST['id_equipo'] ?? ''));
$idEquipo = $equipoRecibido === '' ? null : filter_var($equipoRecibido, FILTER_VALIDATE_INT);
$fechaNacimiento = trim((string) ($_POST['fecha_nacimiento'] ?? ''));

if ($id === false || (is_int($id) && $id < 1)) {
    http_response_code(422);
    exit('El identificador del jugador no es válido.');
}
if (
    $nickname === '' || strlen($nickname) > 50 ||
    $nombres === '' || strlen($nombres) > 50 ||
    $apellidos === '' || strlen($apellidos) > 50 ||
    strlen($posicion) > 30 || strlen($rol) > 30 || strlen($nacionalidad) > 50 ||
    $idEquipo === false || (is_int($idEquipo) && $idEquipo < 1)
) {
    http_response_code(422);
    exit('Revisá los campos y sus longitudes permitidas.');
}

if ($fechaNacimiento !== '') {
    $fecha = DateTime::createFromFormat('!Y-m-d', $fechaNacimiento);
    if (!$fecha || $fecha->format('Y-m-d') !== $fechaNacimiento) {
        http_response_code(422);
        exit('La fecha de nacimiento no es válida.');
    }
} else {
    $fechaNacimiento = null;
}

$datos = [
    'nickname' => $nickname,
    'nombres' => $nombres,
    'apellidos' => $apellidos,
    'posicion' => $posicion !== '' ? $posicion : null,
    'rol' => $rol !== '' ? $rol : null,
    'nacionalidad' => $nacionalidad !== '' ? $nacionalidad : null,
    'fecha_nacimiento' => $fechaNacimiento,
    'id_equipo' => $idEquipo,
];

try {
    if (is_int($id)) {
        $datos['id_jugador'] = $id;
        $sql = <<<'SQL'
            UPDATE jugador
            SET nickname = :nickname, nombres = :nombres, apellidos = :apellidos,
                posicion = :posicion, rol = :rol, nacionalidad = :nacionalidad,
                fecha_nacimiento = :fecha_nacimiento, id_equipo = :id_equipo
            WHERE id_jugador = :id_jugador
        SQL;
        $mensaje = 'Jugador actualizado correctamente.';
    } else {
        $sql = <<<'SQL'
            INSERT INTO jugador
                (nickname, nombres, apellidos, posicion, rol, nacionalidad,
                 fecha_nacimiento, id_equipo)
            VALUES
                (:nickname, :nombres, :apellidos, :posicion, :rol, :nacionalidad,
                 :fecha_nacimiento, :id_equipo)
        SQL;
        $mensaje = 'Jugador agregado correctamente.';
    }
    $pdo->prepare($sql)->execute($datos);
} catch (PDOException $e) {
    if ($e->getCode() === '23000') {
        http_response_code(409);
        exit('No se pudo guardar el jugador porque algún dato relacionado no es válido.');
    }
    http_response_code(500);
    exit('No se pudo guardar el jugador.');
}

redirigirConMensaje($mensaje);
