<?php
declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

function escapar(string|int|float|null $valor): string
{
    return htmlspecialchars((string) $valor, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function tokenCsrf(): string
{
    if (!isset($_SESSION['token_csrf'])) {
        $_SESSION['token_csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['token_csrf'];
}

function validarCsrf(): void
{
    $enviado = $_POST['token_csrf'] ?? '';
    $guardado = $_SESSION['token_csrf'] ?? '';
    if (!is_string($enviado) || !is_string($guardado) || !hash_equals($guardado, $enviado)) {
        http_response_code(403);
        exit('No se pudo validar la solicitud. Volvé a cargar la página.');
    }
}

function redirigirConMensaje(string $mensaje): never
{
    header('Location: index.php?mensaje=' . rawurlencode($mensaje));
    exit;
}
