<?php
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
?>