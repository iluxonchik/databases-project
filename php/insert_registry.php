<?php
/*************************************************************
Insert new registry
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

function update_register_table($dbh, $reg_name, $cnt_seq, $is_active, $typecont) {
    $query = "SELECT regcounter FROM registo ORDER BY regcounter DESC LIMIT 1;";
    $sth = $dbh->prepare($query);
    $sth->execute();
    if($sth->rowCount()) {
        $row = $sth->fetch(PDO::FETCH_ASSOC);
        $reg_cnt = $row['regcounter'] + 1;
    } else {
        // Table is empty, use id 1
        $reg_cnt = 1;
    }
    $query = "INSERT INTO registo (userid,typecounter, regcounter, nome, idseq, ativo) VALUES (?,?, ?, ?, ?, 1);";
    $sth = $dbh->prepare($query);
    $sth->execute(array(get_logged_in_userid(),$typecont, $reg_cnt, $reg_name , $cnt_seq));
}




function update_sequencia_table($dbh) {
        $query = "SELECT contador_sequencia FROM sequencia ORDER BY contador_sequencia DESC LIMIT 1";
        $sth = $dbh->prepare($query);
 
        $sth->execute();
        if($sth->rowCount()) {
            $row = $sth->fetch(PDO::FETCH_ASSOC);
            $cnt_seq = $row['contador_sequencia'] + 1;
        } else {
            // Table empty, use id 1
            $cnt_seq = 1;
        }
        
        $query = "INSERT INTO sequencia(contador_sequencia, moment, userid) VALUES(?, ?, ?);";
        $timestamp = get_curr_timestamp();
        $userid = get_logged_in_userid(); // TODO: null check
        $sth = $dbh->prepare($query);
        $sth->execute(array($cnt_seq, $timestamp, $userid));
        
        return $cnt_seq;
}


if (is_logged_in()) {
    show_header();
    
    if(isset($_POST['typecnt']) && isset($_POST['register_name'])) {
        $register_name = $_POST['register_name'];
        $typecnt=$_POST['typecnt'];
        $dbh = get_database_handler();
        try {
            //$dbh->query(TRANSACTION_START);
            $cnt_seq = update_sequencia_table($dbh);
            update_register_table($dbh, $register_name, $cnt_seq, 1,$typecnt);
            //$dbh->query(TRANSACTION_END);       
        } catch (PDOException $e) {
            echo('<p>ERROR: {' . $e->getMessage() . '}</p>');
        }
 
        $dbh = null; 
        
    }
    else{
?>

<p> Insert new register </p>
<form method = "post" action = "<?php echo $_SERVER['PHP_SELF']; ?>">
Register type:<br><select name="typecnt">
	<?php    
		$dbh=get_database_handler();
		$query='SELECT typecnt,nome FROM tipo_registo WHERE userid=?;';
		$result=$dbh->prepare($query);
		$result->execute(array(get_logged_in_userid()));
		foreach($result as $option){
			echo("<option value=".$option['typecnt'].">".$option['nome']."</option>");
		}
	?>
</select>
<fieldset>
    <label for="page_name">Register name:</label>
    <input type="text" id="register_name" name="register_name" value=" " /> <br />
    <input type="submit" name="submit" value="Add Register" />
</fieldset>
</form>
 
<?php
	}
} else {
    redirect_to_login();
}
?>