<?php
require_once('appfunctions.php');

if(is_logged_in()) {
    show_header();
} else {
    redirect_to_login();
}
?>


<?php
/******************************************************
Create registry with pageid(page to enter registry)  name
/*****************************************************/
	if(isset($_REQUEST['pageid']) && isset($_REQUEST['reg_name'])){
		$dbh=get_database_handler();
		$query = 'SELECT userid FROM reg_pag WHERE pageid=?';
		$sth= $dbh->prepare($query);
		try{
			$sth->execute(array($_POST['pageid']));
			 
			 if($sth['userid'] == $_SESSION['userid']){
				$nextTypeCounter=$dbh->query('SELECT typecnt FROM tipo_registo ORDER BY typecnt DESC LIMIT 1')['typecnt']+1;
				$nextRegCounter=$dbh->query('SELECT regcounter FROM registo ORDER BY regcounter DESC LIMIT 1')['regcounter']+1;
				$nextSeqCounter=$dbh->query('SELECT contador_sequencia FROM sequencia ORDER BY contador_sequencia DESC LIMIT 1')['contador_sequencia']+1;
				$query='INSERT INTO registo(userid,typecounter,regcounter,nome,ativo,idseq,pregcounter)
						values(?,?,?,?,1,?,NULL)';
				echo ($query);
				$sth=$dbh->prepare($query);
				$sth->execute(array($_REQUEST["userid"],$nextTypeCounter,$regcounter,$_REQUEST["name"],$nextSeqCounter));
				
				echo("<table border=\"0\" cellspacing=\"5\">\n");
				echo("<tr>\n");
				//echo("<td>idregpag</td>");
				//echo("<td>userid</td>");
				echo("<td>regid</td>");
				echo("<td>pageid</td>");
				echo("</tr>\n");
				
				}
				else{
					echo ("Permission denied accessing page " $_REQUEST['pageid']); 
				}
			}
			catch(PDOException $e){
				echo("<p>ERROR: {$e->getMessage()}</p>");
			}
		if(isset($db)){
			$dbh=null;
		}
				
	}

	else{
?>		
    <form method = "post" action = "<?php echo $_SERVER['PHP_SELF']; ?>">
    <fieldset>
		Page to insert:<input type="text" id="pageid" name="pageid" value="pageid" /> <br />
        Registry name:<input type="text" id="reg_name" name="reg_name" value="reg_name" /> <br />
        Registry type:<input type="text" id="reg_type" name="reg_type" value="reg_type" /> <br />
        <input type="submit" name="submit" value="Submit" />
    </fieldset>
    </form>
		
		
<?php	}

?>
