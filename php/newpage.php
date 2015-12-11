<?php
/*************************************************************
Insert new page for logged in user.
**************************************************************/
require_once('appfunctions.php');
require_once('startsession.php');
require_once('connectvars.php');

function parse_is_active() {
    $is_active = 1; // default value
    if (isset($_POST['is_active'])) {
            $is_active = intval($_POST['is_active']);
            // Make sure the value is either 1 or 0
            $is_active = ($is_active == 0 || $is_active == 1) ? $is_active : 1;
    }
    return $is_active;
}

function update_page_table($dbh, $page_name, $cnt_seq, $is_active) {
    $query = "SELECT pagecounter FROM pagina ORDER BY pagecounter DESC LIMIT 1;";
    $sth = $dbh->prepare($query);
    $sth->execute();
    if($sth->rowCount()) {
        $row = $sth->fetch(PDO::FETCH_ASSOC);
        $page_cnt = $row['pagecounter'] + 1;
    } else {
        // Table is empty, use id 1
        $page_cnt = 1;
    }

    $query = "INSERT INTO pagina (userid, pagecounter, nome, idseq, ativa) VALUES (?, ?, ?, ?, ?);";
    $sth = $dbh->prepare($query);
    $sth->execute(array(get_logged_in_userid(), $page_cnt, $page_name , $cnt_seq, $is_active));
}



if (is_logged_in()) {
    show_header();

    if(isset($_POST['page_name'])) {
        $page_name = $_POST['page_name'];
        $is_active = parse_is_active();

        $dbh = get_database_handler();
        try {
            $dbh->beginTransaction();
            $cnt_seq = update_sequencia_table($dbh);
            update_page_table($dbh, $page_name, $cnt_seq, $is_active);
            $dbh->commit();
        } catch (PDOException $e) {
            echo('<p>ERROR: {' . $e->getMessage() . '}</p>');
        }

        $dbh = null;

    }
?>

<p> Insert new page </p>
<form method = "post" action = "<?php echo $_SERVER['PHP_SELF']; ?>">
<fieldset>
    <label for="page_name">Name:</label>
    <input type="text" id="page_name" name="page_name" value="" /> <br />
    <label for="is_active">Is active? (0 == FALSE | 1 == TRUE)</label>
    <input type="number" id="is_active" name="is_active" min="0" max="1" value="1"/>
    <input type="submit" name="submit" value="Add Page" />
</fieldset>
</form>

<?php
} else {
    redirect_to_login();
}
?>
