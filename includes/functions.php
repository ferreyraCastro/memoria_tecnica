<?php
/**
 * Funciones auxiliares generales
 */

// ---------------------------------------------------------
// Cifrado / descifrado reversible (para contraseñas guardadas)
// ---------------------------------------------------------
function encryptString(string $plain): array
{
    $ivlen = openssl_cipher_iv_length('aes-256-cbc');
    $iv = openssl_random_pseudo_bytes($ivlen);
    $cipher = openssl_encrypt($plain, 'aes-256-cbc', hash('sha256', APP_ENCRYPTION_KEY, true), OPENSSL_RAW_DATA, $iv);
    return [
        'data' => base64_encode($cipher),
        'iv' => base64_encode($iv),
    ];
}

function decryptString(string $encoded, string $ivEncoded): string
{
    $cipher = base64_decode($encoded);
    $iv = base64_decode($ivEncoded);
    $plain = openssl_decrypt($cipher, 'aes-256-cbc', hash('sha256', APP_ENCRYPTION_KEY, true), OPENSSL_RAW_DATA, $iv);
    return $plain === false ? '' : $plain;
}

// ---------------------------------------------------------
// Sanitización / salida segura
// ---------------------------------------------------------
function h($value): string
{
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
}

function redirect(string $url): void
{
    header('Location: ' . $url);
    exit;
}

// ---------------------------------------------------------
// Fechas / estados de suscripciones
// ---------------------------------------------------------
function diasHasta(string $fecha): int
{
    $hoy = new DateTime('today');
    $venc = new DateTime($fecha);
    return (int)$hoy->diff($venc)->format('%r%a');
}

/**
 * Devuelve el estado de una suscripción según su fecha de vencimiento.
 * @return array{estado:string,label:string,color:string,icon:string}
 */
function estadoSuscripcion(string $fechaVencimiento, int $diasAlerta = 30): array
{
    $dias = diasHasta($fechaVencimiento);
    if ($dias < 0) {
        return ['estado' => 'vencido', 'label' => 'Vencido', 'color' => 'danger', 'icon' => '🔴'];
    }
    if ($dias <= $diasAlerta) {
        return ['estado' => 'proximo', 'label' => 'Próximo a vencer', 'color' => 'warning', 'icon' => '🟡'];
    }
    return ['estado' => 'vigente', 'label' => 'Vigente', 'color' => 'success', 'icon' => '🟢'];
}

function formatFecha(?string $fecha): string
{
    if (!$fecha) return '-';
    try {
        $d = new DateTime($fecha);
        return $d->format('d/m/Y');
    } catch (Exception $e) {
        return $fecha;
    }
}

function formatFechaHora(?string $fecha): string
{
    if (!$fecha) return '-';
    try {
        $d = new DateTime($fecha);
        return $d->format('d/m/Y H:i');
    } catch (Exception $e) {
        return $fecha;
    }
}

function formatMoneda($valor, string $moneda = 'ARS'): string
{
    if ($valor === null || $valor === '') return '-';
    return $moneda . ' ' . number_format((float)$valor, 2, ',', '.');
}

// ---------------------------------------------------------
// Registro de actividad simple para "últimos modificados"
// ---------------------------------------------------------
function siguienteRuta(string $default = 'index.php'): string
{
    return $_SERVER['HTTP_REFERER'] ?? $default;
}

// ---------------------------------------------------------
// Subida de archivos de documentación
// ---------------------------------------------------------
function extensionesPermitidas(): array
{
    return ['pdf', 'png', 'jpg', 'jpeg', 'gif', 'webp', 'doc', 'docx', 'xls', 'xlsx', 'dwg', 'zip', 'txt'];
}

function nombreArchivoSeguro(string $original): string
{
    $ext = strtolower(pathinfo($original, PATHINFO_EXTENSION));
    $base = pathinfo($original, PATHINFO_FILENAME);
    $base = preg_replace('/[^A-Za-z0-9_\-]/', '_', $base);
    return $base . '_' . uniqid() . '.' . $ext;
}
