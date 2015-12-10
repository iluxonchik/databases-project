<?php 
require_once('appfunctions.php');

if(is_logged_in()) {
    show_header();
    echo '<h1> Paginas Ativas </h1>';
} else {
    redirect_to_login();
}
$remove_url = get_curr_dir() . "/remove.php?type=1";
$viewpage_url = get_curr_dir() . "/viewpage.php?id=";
?>
<p><a href="<?php echo get_curr_dir() . "/newpage.php" ?>">New Page</a>
<table border="1">
<th> Nome </th>
<?php
$dbh = get_database_handler();
$query = "SELECT utilizador.userid as userid, pagina.pagecounter as pagecounter, pagina.nome as nome
FROM utilizador, pagina
WHERE utilizador.userid = pagina.userid AND utilizador.userid=? AND ativa = 1;";
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
    
  echo '<p> NOTA: remocao tambem funciona se uma pagina estiver inativa. Basta tirar "AND ativa = 1" da query para testar. </p>';
} catch (PDOException $e) {
  echo('<p>ERROR: {' . $e->getMessage() . '}</p>');
}
?>
</tr>
</table>
