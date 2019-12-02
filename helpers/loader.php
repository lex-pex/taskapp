<?php

/* - - - - - - - - - - - - - - - - - -
 * Autoload function init() initiate
 * the App with all its helpers
 */


// Autoload Auth Helper Facade
require ROOT. '/helpers/Auth.php';
use Helpers\Auth;

// Autoload Global Helpers
init();

function init() {
    set_old_form_params();
}

// --- Routes ----

  // Global Helper Route Builder
function route(string $route, string $param = '') {
    echo '/'. trim($route, '/') . ($param ? '/' . $param : '');
}

function route_exists(string $r) {
    return array_key_exists($r, require ROOT . '/routes/web.php');
}

function abort($code) {
    if($code === 404)
        Router::response_404();
    exit();
}

function redirect(string $route) {
    header('Location: /' . trim($route, '/ '));
    exit();
}

// ---- Forms ----

// Remember request params on redirect back event
function set_old_form_params() {
    if(count($_REQUEST) > 1) {
        $_SESSION['old_params'] = $_REQUEST;
    }
}

// Remember request params on redirect back event
function unset_old_form_params() {
    unset($_SESSION['old_params']);
}

  // Helper for fulfilling the forms, preserves the old value
function old($key) {
    if(!empty($_SESSION['old_params'][$key]))
        return $_SESSION['old_params'][$key];
    return '';
}

// ---- Auth and User ----

function user() {
    return Auth::user();
}
// Check if Auth and return the user id
function auth() {
    return isset($_SESSION['auth']) ? $_SESSION['auth']["identifier"] : 0;
}

function guest() {
    return !isset($_SESSION['auth']);
}

function admin() {
    return Auth::user() ? Auth::user()->role === 'admin' : false;
}

function token() {
    return isset($_SESSION['auth']) ? $_SESSION['auth']['csrf_token'] : false;
}

function is_token() {
    $t = isset($_POST['csrf_token']) ? $_POST['csrf_token'] : '';
    return $_SESSION['auth']['csrf_token'] === $t;
}

function csrf_token() {
    return $_SESSION['auth']['csrf_token'];
}














