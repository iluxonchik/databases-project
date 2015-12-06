<?php

$dashboard = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/dashboard.php';
$logout = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/logout.php';
$login = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/login.php';

function is_logged_in(){
    require_once('startsession.php');
    return isset($_SESSION['userid']);
}

/* Get database handler */
function get_database_handler() {
    require_once('connectvars.php');
    return new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER,  DB_PASSWORD, 
                                                    array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
}

function show_header() {
    global $dashboard, $logout;
    echo '<p>Logged in as ' . $_SESSION['username'] . 
    ' <a href=" ' . $dashboard . '"> Dashboard</a> | <a href = ' . $logout . '>Logout</a> <br/>';
}

function redirect_to_login() {
    global $login;
    header('Location: ' . $login);
}
?>
