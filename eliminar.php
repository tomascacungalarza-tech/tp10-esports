<?php
declare(strict_types=1);

require __DIR__ . '/conexion.php';
require __DIR__ . '/funciones.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Método no permitido.');
}
validarCsrf();

$id = filter_var($_POST['id_jugador'] ?? '', FILTER_VALIDATE_INT);
if (!is_int($id) || $id < 1) {
    http_response_code(422);
    exit('El identificador del jugador no es válido.');
}

$eliminar = $pdo->prepare('DELETE FROM jugador WHERE id_jugador = :id');
$eliminar->execute(['id' => $id]);
redirigirConMensaje(
    $eliminar->rowCount() > 0
        ? 'Jugador eliminado correctamente.'
        : 'No se encontró el jugador solicitado.'
);
