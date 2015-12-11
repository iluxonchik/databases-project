<?php
/*************************************************************
Insert new registry type logged in user.
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

function update_reg_type_table($dbh, $reg_type_name, $cnt_seq, $is_active) {
    $query = "SELECT typecnt FROM tipo_registo ORDER BY typecnt DESC LIMIT 1;";
    $sth = $dbh->prepare($query);
    $sth->execute();
    if($sth->rowCount()) {
        $row = $sth->fetch(PDO::FETCH_ASSOC);
        $typecnt = $row['typecnt'] + 1;
    } else {
        // Table is empty, use id 1
        $typecnt = 1;
    }

    $query = "INSERT INTO tipo_registo (userid, typecnt, nome, ativo, idseq, ptypecnt) VALUES (?, ?, ?, ?, ?, ?);";
    $sth = $dbh->prepare($query);
    $sth->execute(array(get_logged_in_userid(), $typecnt, $reg_type_name , $is_active, $cnt_seq, NULL));
}

if (is_logged_in()) {
    show_header();

    if(isset($_POST['reg_type_name'])) {
        $reg_type_name = $_POST['reg_type_name'];
        $is_active = parse_is_active();

        $dbh = get_database_handler();
        try {
            $dbh->beginTransaction();
            $cnt_seq = update_sequencia_table($dbh);
            update_reg_type_table($dbh, $reg_type_name, $cnt_seq, $is_active);
            $dbh->commit();
        } catch (PDOException $e) {
            echo('<p>ERROR: {' . $e->getMessage() . '}</p>');
        }

        $dbh = null;
        echo "<br/>Added new registry type. Name: $reg_type_name <br/>";
        //TODO: link para inserir fields
    }
?>

<p> Insert new registry type </p>
<form method = "post" action = "<?php echo $_SERVER['PHP_SELF']; ?>">
<fieldset>
    <label for="reg_type_name">Name:</label>
    <input type="text" id="reg_type_name" name="reg_type_name" value="" /> <br />
    <label for="is_active">Is active? (0 == FALSE | 1 == TRUE)</label>
    <input type="number" id="is_active" name="is_active" min="0" max="1" value="1"/>
    <input type="submit" name="submit" value="Add Registry Type" />
</fieldset>
</form>

<?php
} else {
    redirect_to_login();
}

?>
