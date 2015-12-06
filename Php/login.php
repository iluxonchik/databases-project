<?php
require_once('startsession.php');
require_once('connectvars.php');
require_once('appfunctions.php');

define('TIMESTAMP_FORMAT', 'Y-m-d H:i:s');

if(!is_logged_in()) {
    
    if(!isset($username)) {
        $username = '';
    }
    
    if(isset($_POST['submit'])) {
                if ($_POST['username'] != '' && user_exists($_POST['username'])) {
                    // User is not logged in and tried to login
                    $dbh = get_database_handler();
                    $query = 'SELECT userid, email FROM utilizador WHERE email=? AND password=? LIMIT 1;';
                    $sth = $dbh->prepare($query);
                    $userid = null;
                    try {
                        // not our fault, the provided database stores passwords in plain text
                        $sth->execute(array($_POST['username'], $_POST['password']));
                        $login_timestamp = date(TIMESTAMP_FORMAT);
                        if($sth->rowCount()) {
                            // Login success
                            $row = $sth->fetch(PDO::FETCH_ASSOC);
                            // NOTE: what if userid changes in between queries? Make a TRANSACTION?
                            $userid = $row['userid'];
                            $_SESSION['userid'] = $row['userid'];
                            $_SESSION['username'] = $row['email'];
                            setcookie('userid', $row['userid'], time() + (60 * 60 * 24 * 30));     // expires in 30 days
                            setcookie('username', $row['email'], time() + (60 * 60 * 24 * 30)); // expires in 30 days
                            if ($userid != null) {
                                // this should always be executed, in this block, just a sanity check
                                log_login_attempt($userid, 1, $login_timestamp);
                            }
                            header('Location: ' . $dashboard); 
                            //TODO: if third failed attempt in a row, request security question
                        } else {
                            // TODO: show wrong password message
                            // Login failure
                            try {
                                $userid = get_user_id($_POST['username']);
                                if ($userid != null) {
                                    // this should always be executed, in this block, just a sanity check
                                    log_login_attempt($userid, 0, $login_timestamp);
                                }
                            } catch (PDOException $e) {
                                echo('<p>ERROR: {' . $e->getMessage() . '}</p>');
                            }
                        }
                    } catch (PDOException $e) {
                        echo('<p>ERROR: {' . $e->getMessage() . '}</p>');
                    }
                    
                    if (isset($dbh)) {
                        $dbh = null;
                    }
               } else {
                   // TODO: show no such user msg
               }      
    }
?>
    <form method = "post" action = "<?php echo $_SERVER['PHP_SELF']; ?>">
    <fieldset>
        <label for="user_username">Email:</label>
        <input type="text" id="username" name="username" value="<?php echo $username; ?>" /> <br />
        <label for="password">Password:</label>
        <input type="text" id="password" name="password" />
        <input type="submit" name="submit" value="Log In" />
    </fieldset>
    </form>
    
<?php
} else {
    // User logged in
    show_header();
}
?>
