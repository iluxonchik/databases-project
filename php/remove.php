<?php
/*************************************************************
Used for general removal of stuff, based on $_GET['type']

$_GET['type'] == 1 -> remove page with id $_GET['id']
$_GET['type'] == 2 -> remove registry type with id $_GET['id']
$_GET['type'] == 3 -> remove field registry type with registry
    type id $_GET['id'] and field if $_GET['id_field']

**************************************************************/
require_once('appfunctions.php');

define('REDIR_DELTA', 3);
define('REDIRECT_MSG', 'Redirecting to previous page... <a href="' . get_prev_url() . 
'">Click here</a> if that doesen\'t happpen in ' . REDIRECT_MSG . ' seconds.');


function handle_page_removal() {
    // TODO
}

function handle_reg_removal() {
    // TODO
}

function handle_reg_type_removal() {
    // TODO
}

if(is_logged_in()) {
    if (isset($_GET['type'])) {
        $type = $_GET['type'];
    } else {
        $msg = '<p> ERROR: Type not set </p> <br />' . '<p>' . REDIRECT_MSG . '</p>';
        redirect_with_message(get_prev_url(), $msg, REDIR_DELTA);
    }
    
    switch($type) {
        case 1:
            // Remove page
            handle_page_removal();
            break;
        case 2:
            // Remove registry
            handle_reg_removal();
            break;
        case 3:
            // Remove field registry type
            handle_reg_field_removal();
            break;
    }
    
} else {
    redirect_to_login();
}

?>