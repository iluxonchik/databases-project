<?php
require_once('startsession.php');
require_once('connectvars.php');
require_once('appfunctions.php');

$dashboard = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/dashboard.php';

if(!is_logged_in()) {
	
	if(!isset($username)) {
		$username = '';
	}
	
	if(isset($_POST['submit'])) {
		// User is not logged in and tried to login
		        $dbh = get_database_handler();
				$query = 'SELECT * FROM utilizador WHERE email=? AND password=? LIMIT 1;';
				$sth = $dbh->prepare($query);
				try {
				$sth->execute(array($_POST['username'], $_POST['password']));
				
					if($sth->rowCount()) {
						// Login success
						$row = $sth->fetch(PDO::FETCH_ASSOC);
						$_SESSION['userid'] = $row['userid'];
						$_SESSION['username'] = $row['username'];
						setcookie('userid', $row['userid'], time() + (60 * 60 * 24 * 30));     // expires in 30 days
						setcookie('username', $row['username'], time() + (60 * 60 * 24 * 30)); // expires in 30 days
						header('Location: ' . $dashboard); 
						// TODO: log login attempt
					}
				} catch (PDOException $e) {
        			echo('<p>ERROR: {' . $e->getMessage() . '}</p>');
    		    }
							
	}
?>
	<form method = "post" action = "<?php echo $_SERVER['PHP_SELF']; ?>">
	<fieldset>
		<label for="user_username">Username:</label>
		<input type="text" id="username" name="username" value="<?php echo $username; ?>" />
		<label for="password">Password:</label>
		<input type="text" id="password" name="password" />
		<input type="submit" name="submit" value="Log In" />
	</fieldset>
	</form>
	
<?php
} else {
	// User logged in
	echo '<p>Logged in as ' . $_SESSION['username'] . ' <a href=" ' . $dashboard . '"> Dashboard </a>';
}
?>