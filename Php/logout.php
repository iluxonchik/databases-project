<?php
require_once('startsession.php');
require_once('appfunctions.php');

$login = $dashboard = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/login.php';

if(is_logged_in()) {
	if(isset($_SESSION['userid'])) {
		// Delete session vars by clearing the $_SESSION array
		$_SESSION = array();
	
	// Delete the session cookie by setting its expiration to an hour ago
	if(isset($_COOKIE[session_name()])) {
		setcookie(session_name(), '', time() - 3600);
	}
	
	// Destroy the session
	session_destroy();
	
	// Delete the user ID and username cookies by setting their expiration date to an hour ago
	setcookie('userid', '', time() - 3600);
	setcookie('username', '', time() - 3600);
	}
	
	header("Location: $login");
}

?>