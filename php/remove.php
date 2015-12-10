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

function insert_new_sequencia($dbh) {
    $query = 'SELECT contador_sequencia FROM sequencia ORDER BY contador_sequencia DESC LIMIT 1;';
    $sth = $dbh->prepare($query);
    $sth->execute();
    
    if ($sth->rowCount()) {
       $row = $sth->fetch(PDO::FETCH_ASSOC);
       return $row['contador_sequencia'] + 1;
    }
    return null;
}

function clone_page($dbh) {
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
    $new_pagecounter = $pagecounter + 1;
    $sth->execute(array($userid, $new_pagecounter, $nome, $idseq, 0, $ppagecounter));
    
    return array(
        'userid' => $userid,
        'pagecounter' => $pagecounter,
        'ativa' => $ativa, // TODO: null check
        'ppagecounter' => $new_pagecounter
    );
}

function update_page_info($params) {
    $idseq = insert_new_sequencia($dbh);
    if ($idseq == null) {
        $idseq = 1;
    }
    $query = 'UPDATE TABLE pagina
              SET idseq = ?, ativa = ?, ppagecounter = ?
              WHERE pagecounter = ? AND userid = ?;';
    $sth = $dbh->prepare($query);
    $sth->execute(array($idseq, $params['ativa'], $params['ppagecounter'], 
    $params['pagecounter'], $params['userid']));
}

function handle_page_removal() {
    if(isset($_GET['id'])) {
       $id = $_GET['id'];
       $dbh = get_database_handler();
       try {
           $dbh->query(TRANSACTION_START);
           $params = clone_page($dbh);
           update_page_info($dbh, $params);
           $dbh->query(TRANSACTION_END);

       } catch (PDOException $e) {
            echo('<p>ERROR: {' . $e->getMessage() . '}</p>');
       }
       $dbh = null;
    } else {
        
    }
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