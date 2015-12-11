<?php
/*************************************************************
Insert new field  TODO:Assume que recebe typecnt
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

function update_field_table($dbh, $field_name, $cnt_seq, $is_active, $typecont) {
    $query = "SELECT campocnt FROM campo WHERE userid=? ORDER BY campocnt DESC LIMIT 1;";
    $sth = $dbh->prepare($query);
    $sth->execute(array(get_logged_in_userid()));
    if($sth->rowCount()) {
        $row = $sth->fetch(PDO::FETCH_ASSOC);
        $field_cnt = $row['regcounter'] + 1;
    } else {
        // Table is empty, use id 1
        $field_cnt = 1;
    }
    $query = "INSERT INTO campo (userid,typecnt, campocnt, nome, idseq, ativo) VALUES (?,?, ?, ?, ?, 1);";
    $sth = $dbh->prepare($query);
    $sth->execute(array(get_logged_in_userid(),$typecont, $field_cnt, $field_name , $cnt_seq));
}





if (is_logged_in()) {
    show_header();
    
    if(isset($_POST['field_name'])) {
        $field_name = $_POST['field_name'];
        $typecnt=$_POST['typecnt'];
        $dbh = get_database_handler();
        try {
            $dbh->beginTransaction();
            $cnt_seq = update_sequencia_table($dbh);
            update_field_table($dbh, $field_name, $cnt_seq, 1,$typecnt);
            $dbh->commit();       
        } catch (PDOException $e) {
            echo('<p>ERROR: {' . $e->getMessage() . '}</p>');
        }
 
        $dbh = null; 
        
    }
    else{
?>

<p> Insert new field </p>
<form method = "post" action = "<?php echo $_SERVER['PHP_SELF']; ?>">
<?php 
if(!isset($_POST['typecnt'])){ ?>
<select name="typecnt">
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
<?php 
}
?>
<fieldset>
    <label for="page_name">Name:</label>
    <input type="text" id="field_name" name="field_name" value="" /> <br />
    <input type="submit" name="submit" value="Add Field <?php 
										if(isset($_POST['typecnt']))
											echo("to ".$_REQUEST['typecnt'])?>" />
</fieldset>
</form>
 
<?php
	}
} else {
    redirect_to_login();
}
?>