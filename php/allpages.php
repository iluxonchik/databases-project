<?php 
/*
    Yes, this should be done inside pages.php, but we have less than 13hrs till deadline.
*/
require_once('appfunctions.php');

if(is_logged_in()) {
    show_header();
    echo '<h1> Paginas Ativas E Inativas </h1>';
} else {
    redirect_to_login();
}
$remove_url = get_curr_dir() . "/remove.php?type=1";
$viewpage_url = get_curr_dir() . "/viewpage.php?pageid=";
$activepages_url = get_curr_dir() . '/pages.php';
?>
<p><a href="<?php echo get_curr_dir() . "/newpage.php" ?>">New Page</a>
<table border="1">
<th> Nome </th>
<?php
$dbh = get_database_handler();
$query = "SELECT utilizador.userid as userid, pagina.pagecounter as pagecounter, pagina.nome as nome
FROM utilizador, pagina
WHERE utilizador.userid = pagina.userid AND utilizador.userid=?;";
$sth = $dbh->prepare($query);
try {
    $sth->execute(array($_SESSION['userid']));
    if ($sth->rowCount()) {
        $rows = $sth->fetchAll(PDO::FETCH_ASSOC);
        foreach($rows as $row) {
            echo '<tr><td>' . $row['nome'] .' | ' .  generate_anchor('Abrir', $viewpage_url . $row['pagecounter']) .
            ' | ' . generate_anchor('Remover', $remove_url . '&id=' .  $row['pagecounter']) . '</td></tr>';
        }
    }
    
  echo '<p> NOTA: a mostrar paginas ativas e inativas. <a href="' . $activepages_url . '">Clique aqui</a> para versao onde so as paginas ativas sao mostradas.</p>';
} catch (PDOException $e) {
  echo('<p>ERROR: {' . $e->getMessage() . '}</p>');
}
?>
</tr>
</table>