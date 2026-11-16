<?php
declare(strict_types=1);

require __DIR__ . '/conexion.php';
require __DIR__ . '/funciones.php';

$busqueda = trim((string) ($_GET['q'] ?? ''));
$sql = <<<'SQL'
    SELECT j.id_jugador, j.nickname, j.nombres, j.apellidos,
           j.posicion, j.rol, j.nacionalidad, j.fecha_nacimiento,
           e.id_equipo, e.nombre AS equipo
    FROM jugador AS j
    LEFT JOIN equipo AS e ON e.id_equipo = j.id_equipo
SQL;
if ($busqueda !== '') {
    $sql .= ' WHERE j.nickname LIKE :nickname OR j.nombres LIKE :nombres '
          . 'OR j.apellidos LIKE :apellidos OR j.rol LIKE :rol '
          . 'OR j.posicion LIKE :posicion OR e.nombre LIKE :equipo';
}
$sql .= ' ORDER BY j.nickname';
$consulta = $pdo->prepare($sql);
if ($busqueda !== '') {
    $coincidencia = '%' . $busqueda . '%';
    $consulta->execute([
        'nickname' => $coincidencia,
        'nombres' => $coincidencia,
        'apellidos' => $coincidencia,
        'rol' => $coincidencia,
        'posicion' => $coincidencia,
        'equipo' => $coincidencia,
    ]);
} else {
    $consulta->execute();
}
$jugadores = $consulta->fetchAll();
$equipos = $pdo->query('SELECT id_equipo, nombre FROM equipo ORDER BY nombre')->fetchAll();

$jugadorEditar = null;
$idEdicion = filter_input(INPUT_GET, 'editar', FILTER_VALIDATE_INT);
if (is_int($idEdicion) && $idEdicion > 0) {
    $edicion = $pdo->prepare('SELECT * FROM jugador WHERE id_jugador = :id');
    $edicion->execute(['id' => $idEdicion]);
    $jugadorEditar = $edicion->fetch() ?: null;
}
$token = tokenCsrf();
$mensaje = (string) ($_GET['mensaje'] ?? '');
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestión de jugadores de Esports</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.9/dist/css/bootstrap.min.css"
          rel="stylesheet"
          integrity="sha384-yEPZ7jP9S0QKiUQ0m3jjsO4ProIW7wvR9gXkI7JOGa2XVsw53Yke2bsdkBbjmmiX"
          crossorigin="anonymous">
</head>
<body class="bg-light">
<main class="container py-4">
    <header class="mb-4">
        <h1 class="h2">Sistema de gestión de Esports</h1>
        <p class="text-secondary mb-0">Administración de jugadores y su relación con equipos.</p>
    </header>

    <?php if ($mensaje !== ''): ?>
        <div class="alert alert-info" role="status"><?= escapar($mensaje) ?></div>
    <?php endif; ?>

    <section class="card mb-4">
        <div class="card-body">
            <h2 class="h5"><?= $jugadorEditar ? 'Editar jugador' : 'Registrar jugador' ?></h2>
            <form action="guardar.php" method="post" class="row g-3">
                <input type="hidden" name="token_csrf" value="<?= escapar($token) ?>">
                <input type="hidden" name="id_jugador"
                       value="<?= escapar($jugadorEditar['id_jugador'] ?? '') ?>">
                <div class="col-md-4">
                    <label for="nickname" class="form-label">Nickname</label>
                    <input class="form-control" id="nickname" name="nickname" maxlength="50" required
                           value="<?= escapar($jugadorEditar['nickname'] ?? '') ?>">
                </div>
                <div class="col-md-4">
                    <label for="nombres" class="form-label">Nombre</label>
                    <input class="form-control" id="nombres" name="nombres" maxlength="50" required
                           value="<?= escapar($jugadorEditar['nombres'] ?? '') ?>">
                </div>
                <div class="col-md-4">
                    <label for="apellidos" class="form-label">Apellido</label>
                    <input class="form-control" id="apellidos" name="apellidos" maxlength="50" required
                           value="<?= escapar($jugadorEditar['apellidos'] ?? '') ?>">
                </div>
                <div class="col-md-4">
                    <label for="posicion" class="form-label">Posición</label>
                    <input class="form-control" id="posicion" name="posicion" maxlength="30"
                           value="<?= escapar($jugadorEditar['posicion'] ?? '') ?>">
                </div>
                <div class="col-md-4">
                    <label for="rol" class="form-label">Rol</label>
                    <input class="form-control" id="rol" name="rol" maxlength="30"
                           placeholder="Ej.: Support, DPS o Tank"
                           value="<?= escapar($jugadorEditar['rol'] ?? '') ?>">
                </div>
                <div class="col-md-4">
                    <label for="nacionalidad" class="form-label">Nacionalidad</label>
                    <input class="form-control" id="nacionalidad" name="nacionalidad" maxlength="50"
                           value="<?= escapar($jugadorEditar['nacionalidad'] ?? '') ?>">
                </div>
                <div class="col-md-6">
                    <label for="fecha_nacimiento" class="form-label">Fecha de nacimiento</label>
                    <input class="form-control" type="date" id="fecha_nacimiento" name="fecha_nacimiento"
                           value="<?= escapar($jugadorEditar['fecha_nacimiento'] ?? '') ?>">
                </div>
                <div class="col-md-6">
                    <label for="id_equipo" class="form-label">Equipo</label>
                    <select class="form-select" id="id_equipo" name="id_equipo">
                        <option value="">Sin equipo asignado</option>
                        <?php foreach ($equipos as $equipo): ?>
                            <option value="<?= escapar($equipo['id_equipo']) ?>"
                                <?= (string) ($jugadorEditar['id_equipo'] ?? '') === (string) $equipo['id_equipo'] ? 'selected' : '' ?>>
                                <?= escapar($equipo['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-12">
                    <button class="btn btn-primary" type="submit">
                        <?= $jugadorEditar ? 'Guardar cambios' : 'Agregar jugador' ?>
                    </button>
                    <?php if ($jugadorEditar): ?>
                        <a class="btn btn-outline-secondary" href="index.php">Cancelar edición</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </section>

    <section class="card">
        <div class="card-body">
            <div class="d-flex flex-wrap justify-content-between gap-3 mb-3">
                <h2 class="h5 mb-0">Jugadores registrados</h2>
                <form method="get" class="d-flex gap-2">
                    <label class="visually-hidden" for="q">Buscar</label>
                    <input class="form-control" id="q" name="q"
                           placeholder="Nickname, nombre, rol o equipo"
                           value="<?= escapar($busqueda) ?>">
                    <button class="btn btn-outline-primary" type="submit">Buscar</button>
                    <?php if ($busqueda !== ''): ?>
                        <a class="btn btn-outline-secondary" href="index.php">Limpiar</a>
                    <?php endif; ?>
                </form>
            </div>
            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead>
                    <tr>
                        <th>Nickname</th><th>Nombre completo</th><th>Posición</th>
                        <th>Rol</th><th>Equipo</th><th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($jugadores as $jugador): ?>
                        <tr>
                            <td><?= escapar($jugador['nickname']) ?></td>
                            <td><?= escapar($jugador['nombres'] . ' ' . $jugador['apellidos']) ?></td>
                            <td><?= escapar($jugador['posicion']) ?></td>
                            <td><?= escapar($jugador['rol']) ?></td>
                            <td><?= escapar($jugador['equipo'] ?? 'Sin equipo') ?></td>
                            <td class="text-nowrap">
                                <a class="btn btn-sm btn-outline-primary"
                                   href="?editar=<?= escapar($jugador['id_jugador']) ?>">Editar</a>
                                <form action="eliminar.php" method="post" class="d-inline"
                                      onsubmit="return confirm('¿Eliminar este jugador?')">
                                    <input type="hidden" name="token_csrf" value="<?= escapar($token) ?>">
                                    <input type="hidden" name="id_jugador" value="<?= escapar($jugador['id_jugador']) ?>">
                                    <button class="btn btn-sm btn-outline-danger" type="submit">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if ($jugadores === []): ?>
                        <tr><td colspan="6" class="text-center text-secondary">No se encontraron jugadores.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</main>
</body>
</html>
