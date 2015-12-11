<?php
require_once('appfunctions.php');

if(is_logged_in()) {
    show_header();
} else {
    redirect_to_login();
}
$remove_url = get_curr_dir() . "/remove.php?type=3&typecnt=";
?>


<?php
    if(isset($_REQUEST['typecnt'])){
        $dbh=get_database_handler();
        $dbh->beginTransaction();
        $query = 'SELECT t.typecnt,c.typecnt,c.campocnt,c.nome FROM tipo_registo as t, campo as c
WHERE t.userid=c.userid AND c.userid=? AND c.ativo=1 AND t.ativo=1 AND t.typecnt=c.typecnt AND t.typecnt=?
GROUP BY c.nome;';
        $sth= $dbh->prepare($query);
        try{
            $sth->execute(array( $_SESSION['userid'], $_REQUEST['typecnt'] ));
            echo("<h2>campos do registo {$_REQUEST['typecnt']}</h2>");
            echo("<table border=\"1\" cellspacing=\"5\">\n");
            echo("<tr>\n");
            echo("<th>campocnt</th>");
            echo("<th>nome</th>");
            echo("<th></th>");
            echo("</tr>\n");

            foreach($sth as $row)
            {
                echo("<tr>\n");
                //echo("<td>{$row['idregpag']}</td>\n");
                echo("<td>{$row['campocnt']}</td>\n");
                echo("<td>{$row['nome']}</td>\n");
                echo("<td>".generate_anchor('Remover', $remove_url . $row['typecnt']."&campocnt=".$row['campocnt'])."</td>\n");
                echo("</tr>\n");
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
    <form method = "get" action = "<?php echo $_SERVER['PHP_SELF']; ?>">
    <fieldset>
        <input type="text" id="typecnt" name="typecnt" value="typecnt" /> <br />
        <input type="submit" name="submit" value="Submit" />
    </fieldset>
    </form>


<?php   }

?>
