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
View page with page id $_GET['id'] with its registries
/*****************************************************/
	if(isset($_REQUEST['pageid'])){
		$dbh=get_database_handler();
        $dbh->beginTransaction();
		$query = 'SELECT r.nome,rp.regid,rp.pageid,rp.userid from reg_pag rp,registo r where rp.pageid=? group by rp.regid';
		$sth= $dbh->prepare($query);
		try{
			$sth->execute(array($_REQUEST['pageid']));
			 echo("<table border=\"1\" cellspacing=\"5\">\n");
		echo("<tr>\n");
		echo("<td>userid</td>");
		echo("<td>regid</td>");
		echo("<td>pageid</td>");
		echo("<td>Nome de registo</td>");
		echo("</tr>\n");

		foreach($sth as $row)
 		{
		 if($row['userid']  == $_SESSION['userid']){
			echo("<tr>\n");
			//echo("<td>{$row['idregpag']}</td>\n");
			echo("<td>{$row['userid']}</td>\n");
			echo("<td>{$row['regid']}</td>\n");
			echo("<td>{$row['pageid']}</td>\n");
			echo("<td>{$row['nome']}</td>\n");
 			echo("</tr>\n");
			}
 		}
		 echo("</table>\n");
		$dbh->commit();
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
        <input type="text" id="username" name="pageid" value="pageid" /> <br />
        <input type="submit" name="submit" value="Submit" />
    </fieldset>
    </form>


<?php	}

?>
