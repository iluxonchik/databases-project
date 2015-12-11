<?php
require_once('appfunctions.php');

if(is_logged_in()) {
    show_header();
    echo '<h1> Tipos de registo ativos </h1>';
} else {
    redirect_to_login();
}
$remove_url = get_curr_dir() . "/remove.php?type=2"; // expects id=...
$viewregtype_url = get_curr_dir() . "/viewregtype.php?id=";


?>
<p><a href="newregtype.php">Criar tipo de registo</a>

<table border="1">
<tr> <th>Nome</th> <th></th>  </tr>
<?php
$dbh = get_database_handler();
$query = "SELECT tipo_registo.userid as userid, tipo_registo.typecnt as typecnt, tipo_registo.nome as nome
FROM utilizador, tipo_registo
WHERE utilizador.userid = tipo_registo.userid AND utilizador.userid=? AND ativo = 1;";
$sth = $dbh->prepare($query);
try {
    $sth->execute(array($_SESSION['userid']));
    if ($sth->rowCount()) {
        $rows = $sth->fetchAll(PDO::FETCH_ASSOC);
        foreach($rows as $row) {
            echo '<tr><td>' . $row['nome'] .' </td><td> ' ./*  generate_anchor('Abrir', $viewregtype_url . $row['typecnt']) .
            ' | ' .*/ generate_anchor('Remover', $remove_url . '&id=' .  $row['typecnt']) . '</td></tr>';
        }
    }

  //echo '<p> NOTA: remocao tambem funciona se uma pagina estiver inativa. <a href="' . $allpages_url . '">Clique aqui</a> para versao onde todas as paginas sao mostradas.</p>';
} catch (PDOException $e) {
  echo('<p>ERROR: {' . $e->getMessage() . '}</p>');
}
?>
</tr>
</table>
