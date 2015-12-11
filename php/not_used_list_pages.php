

<?php 
require_once('appfunctions.php');

if(is_logged_in()) {
    show_header();
} else {
    redirect_to_login();
}
try{
	$dbh=get_database_handler();
	$query='SELECT nome,rp.pageid,rp.userid FROM reg_pag rp,pagina p WHERE rp.userid='.$_SESSION['userid'].' and pageid=pagecounter GROUP BY pageid';
	$sth=$dbh->prepare($query);
	$sth->execute();

		echo("<table border=\"1\" cellspacing=\"5\">\n");
		echo("<tr>\n");
		echo("<td>userid</td>");
		echo("<td>Nome da pagina</td>");
		echo("</tr>\n");
		
		foreach($sth as $row)
 		{
			echo("<tr>\n");
			echo("<td>{$row['userid']}</td>\n");
			echo("<td><a href='viewpage.php?pageid={$row['pageid']}'>{$row['nome']}</a></td>\n");
 			echo("</tr>\n");
 		}
		 echo("</table>\n");

}
catch(PDOException $e){
	echo("<p>ERROR: {$e->getMessage()}</p>");
}


?>