<?php

function e($string)
{
    // Accepter null et autres types en les castant en chaîne pour éviter les warnings
    if ($string === null) {
        $string = '';
    }
    return htmlspecialchars((string)$string, ENT_QUOTES, 'UTF-8');
}

function csrf_token()
{
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function set_flash_message($message, $type = 'info')
{
    set_flash($type, $message);
}

function set_flash(string $key, $message): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['flash'])) {
        $_SESSION['flash'] = [];
    }

    $_SESSION['flash'][$key] = $message;
}

function get_flash(string $key, $default = null)
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['flash'][$key])) {
        return $default;
    }

    $message = $_SESSION['flash'][$key];
    unset($_SESSION['flash'][$key]);

    return $message;
}

function clean_input($data)
{
    // Accepter null et autres types
    $data = (string)$data;
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

function format_date($date, $format = 'd/m/Y H:i')
{
    return date($format, strtotime($date));
}

function is_post()
{
    return $_SERVER['REQUEST_METHOD'] === 'POST';
}

function post($key, $default = null)
{
    return $_POST[$key] ?? $default;
}

function validate_name($name)
{
    // Entre 2 et 50 caractères
    if (strlen($name) < 2 || strlen($name) > 20) return false;

    // Lettres, espaces et tirets uniquement
    if (!preg_match('/^[a-zA-ZÀ-ÿ\s\-]+$/', $name)) return false;

    return true;
}


function generate_slug($string)
{
    $string = strtolower($string);
    $string = preg_replace('/[^a-z0-9\s-]/', '', $string);
    $string = preg_replace('/[\s-]+/', '-', $string);
    return trim($string, '-');
}
