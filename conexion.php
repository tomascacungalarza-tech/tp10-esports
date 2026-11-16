<?php
declare(strict_types=1);

$archivoConfiguracion = __DIR__ . '/config.php';
if (is_file($archivoConfiguracion)) {
    require $archivoConfiguracion;
}

$host = defined('DB_HOST') ? DB_HOST : (getenv('DB_HOST') ?: '127.0.0.1');
$base = defined('DB_NAME') ? DB_NAME : (getenv('DB_NAME') ?: 'esports_tp10');
$usuario = defined('DB_USER') ? DB_USER : (getenv('DB_USER') ?: 'root');
$clave = defined('DB_PASS') ? DB_PASS : (getenv('DB_PASS') ?: '');
$dsn = "mysql:host={$host};dbname={$base};charset=utf8mb4";

try {
    $pdo = new PDO($dsn, $usuario, $clave, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    exit('No se pudo establecer la conexión con la base de datos.');
}
