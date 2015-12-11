<?php
define('TIMESTAMP_FORMAT', 'Y-m-d H:i:s');


$dashboard = get_curr_dir() . '/dashboard.php';
$logout = get_curr_dir() . '/logout.php';
$login = get_curr_dir() . '/login.php';

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
    echo '<br/>';
}

function redirect_to_login() {
    global $login;
    header('Location: ' . $login);
}

function log_login_attempt($userid, $success, $timestamp) {
    /* exeption must be handled by caller */
    $contador_login  = null;

    $dbh = get_database_handler();
    // get largest contador_login in table "login"
    $query = "SELECT contador_login FROM login ORDER BY contador_login DESC LIMIT 1";
    $sth = $dbh->prepare($query);
    $dbh->beginTransaction();
    $sth->execute();
    $dbh->commit();

    if ($sth->rowCount()){
        // query successful
        $row = $sth->fetch(PDO::FETCH_ASSOC);
        $contador_login = $row['contador_login'] + 1; // increment max value by 1
        error_log("CONTADOR_LOGIN:" . $contador_login);
    }

    if($contador_login != null) {
        $query = "INSERT INTO login (contador_login, userid, sucesso, moment) VALUES (?, ?, ?, ?);";
        $sth = $dbh->prepare($query);
        $sth->execute(array($contador_login, $userid, $success, $timestamp));
        $dbh = null;
    } else {
        // something went wrong during first query
        throw new PDOException('Could not query "login" table.');
    }
}

/* Check if a user exists */
function user_exists($email) {
    /* exeption must be handled by caller */
    return get_user_id($email) != null;
}

/* Gets user's ID based on email */
function get_user_id($email) {
    $dbh = get_database_handler();
    $query = "SELECT userid FROM utilizador WHERE email=? LIMIT 1;";
    $sth = $dbh->prepare($query);
    $sth->execute(array($email));
    if ($sth->rowCount()) {
        $row = $sth->fetch(PDO::FETCH_ASSOC);
        $dbh = null;
        return $row['userid'];
    }
    $dbh = null;

    return null;
}

function get_logged_in_userid() {
    if(isset($_SESSION['userid'])) {
        return $_SESSION['userid'];
    }
    return null;
}

function get_curr_dir() {
    return 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
}

function generate_anchor($text, $link) {
    return "<a href=\"{$link}\"> $text</a>";
}

function get_curr_timestamp() {
    return date(TIMESTAMP_FORMAT);
}

function redirect_with_message($url, $msg, $delta = 1) {
     header( "refresh:" . $delta . ".;url=" . $url);
     if ($msg != null) {
         echo $msg;
     }
}

function get_prev_url() {
    if(isset($_SERVER['HTTP_REFERER'])) {
        return $_SERVER['HTTP_REFERER'];
    }
    return $dashboard;
}

function invert_int_bool($val) {
    ($val == 0) ? 1 : 0;
}

function get_max_pagina_pagecounter($dbh) {
    $query = 'SELECT pagecounter FROM pagina ORDER BY pagecounter DESC LIMIT 1;';
    $sth = $dbh->prepare($query);
    $sth->execute();
    if ($sth->rowCount()) {
       $row = $sth->fetch(PDO::FETCH_ASSOC);
       return $row['pagecounter'];
    } else {
        return null; // empty table
    }
}

function get_new_pagina_pagecounter($dbh) {
    $new_seqcounter = get_max_pagina_pagecounter($dbh);
    return ($new_seqcounter == null) ? 1 : $new_seqcounter + 1;
}

function get_max_seq_counter($dbh) {
    $query = 'SELECT contador_sequencia FROM sequencia ORDER BY contador_sequencia DESC LIMIT 1;';
    $sth = $dbh->prepare($query);
    $sth->execute();
    if ($sth->rowCount()) {
       $row = $sth->fetch(PDO::FETCH_ASSOC);
       return $row['contador_sequencia'];
    } else {
        return null; // empty table
    }
}

function get_new_seq_counter($dbh) {
    $new_seqcounter = get_max_seq_counter($dbh);
    return ($new_seqcounter == null) ? 1 : $new_seqcounter + 1;
}

?>
