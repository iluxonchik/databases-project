<?php
/*************************************************************
Used for general removal of stuff, based on $_GET['type']

$_GET['type'] == 1 -> remove page with id $_GET['id']
$_GET['type'] == 2 -> remove registry type with id $_GET['id']
$_GET['type'] == 3 -> remove field registry type with registry
    type id $_GET['id'] and field if $_GET['id_field']

**************************************************************/
require_once('appfunctions.php');

define('REDIRECT_DELTA', 3);
define('REDIRECT_MSG', 'Redirecting to previous page... <a href="' . get_prev_url() .
'">Click here</a> if that doesen\'t happpen in ' . REDIRECT_DELTA . ' seconds.');
define('SUCCESS_MSG', '<p> Page removed successfuly! </p>' . '<p>' . REDIRECT_MSG . '</p>');
define('TYPE_ERR_MSG', '<p> ERROR: Type not set </p> <br />' . '<p>' . REDIRECT_MSG . '</p>');

function insert_new_sequencia($dbh) {
    $query = 'SELECT contador_sequencia, userid FROM sequencia ORDER BY contador_sequencia DESC LIMIT 1;';
    $sth = $dbh->prepare($query);
    $sth->execute();

    if ($sth->rowCount()) {
       $row = $sth->fetch(PDO::FETCH_ASSOC);
       $new_contador_sequencia = $row['contador_sequencia'] + 1;
       $query = 'INSERT INTO sequencia (contador_sequencia, moment, userid) VALUES (?, ?, ?);';
       $sth = $dbh->prepare($query);
       $sth->execute(array($new_contador_sequencia, get_curr_timestamp(), $row['userid']));
       return $new_contador_sequencia;
    }
    return null;
}

function clone_page($dbh, $id) {
    $orig_idseq = null;
    // Get values to clone
    $query = 'SELECT userid, pagecounter, nome, idseq, ativa, ppagecounter FROM pagina
            WHERE userid = ? AND pagecounter = ? LIMIT 1;';
    $sth = $dbh->prepare($query);
    $sth->execute(array(get_logged_in_userid(), $id));
    if($sth->rowCount()) {
        $row = $sth->fetch(PDO::FETCH_ASSOC);
        $userid = $row['userid'];
        $pagecounter = $row['pagecounter'];
        $nome = $row['nome'];
        $ativa = $row['ativa'];
        $orig_idseq = $idseq = $row['idseq'];
        $ppagecounter = $row['ppagecounter'];
    }
    $query = 'INSERT INTO pagina(userid, pagecounter, nome, idseq, ativa, ppagecounter)
              VALUES (?, ?, ?, ?, ?, ?);';
    $sth = $dbh->prepare($query);
    $new_pagecounter = get_new_pagina_pagecounter($dbh);
    $sth->execute(array($userid, $new_pagecounter, $nome, $idseq, 0, $ppagecounter));

    return array(
        'userid' => $userid,
        'pagecounter' => $pagecounter,
        'ativa' => $ativa, // TODO: null check
        'ppagecounter' => $new_pagecounter
    );
}

function update_page_info($dbh, $params) {
    $idseq = insert_new_sequencia($dbh);
    if ($idseq == null) {
        $idseq = 1;
    }
    $query = 'UPDATE pagina
              SET idseq = ?, ativa = ?, ppagecounter = ?
              WHERE pagecounter = ? AND userid = ?;';
    $sth = $dbh->prepare($query);
    $sth->execute(array($idseq, 0, $params['ppagecounter'],
    $params['pagecounter'], $params['userid']));
}

function handle_page_removal() {
    if(isset($_GET['id'])) {
       $id = $_GET['id'];
       $dbh = get_database_handler();
       try {
           $dbh->beginTransaction();
           $params = clone_page($dbh, $id);
           update_page_info($dbh, $params);
           $dbh->commit();

       } catch (PDOException $e) {
            echo('<p>ERROR: {' . $e->getMessage() . '}</p>');
       }
       $dbh = null;
    } else {

    }
    redirect_with_message(get_prev_url(), SUCCESS_MSG, REDIRECT_DELTA);
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
        redirect_with_message(get_prev_url(), TYPE_ERR_MSG, REDIRECT_DELTA);
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
