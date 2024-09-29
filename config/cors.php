<?php
function generarExpresionRegularDesdeURL($url)
{
    // Escapar caracteres especiales en la URL
    $escaped_url = preg_quote($url, '/');

    // Construir la expresión regular
    // Coincidirá con cualquier subdominio y dominio de segundo nivel de la URL actual
    $regex = '/^https?:\/\/(?:.*\.)?' . $escaped_url . '$/';

    return $regex;
}

// Obtener la URL actual sin ningún elemento adicional
function obtenerURLActual()
{
    global $_SERVER;
    $protocolo = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
    $host = isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : env('APP_URL_OFF_PROTOCOL');
    return $protocolo . "://" . $host;
}

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
     */

    'paths' => ['*', 'api/*'],

    'allowed_methods' => ['*'],

    'allowed_origins' => explode(',', env('ALLOWED_ORIGINS')),

// Generar la expresión regular desde la URL actual
    'allowed_origins_patterns' => [generarExpresionRegularDesdeURL(obtenerURLActual())],

    'allowed_headers' => ['*'],

    'exposed_headers' => ['*'],

    'max_age' => 0,

    'supports_credentials' => true];
