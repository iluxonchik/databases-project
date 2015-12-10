<html>
 <body>
 <h3>Accounts</h3>
<?php

include "login_data.php";
 try
 {
 $host = "db.ist.utl.pt";
 $dbname = $username;

 $db = new PDO("mysql:host=$host;dbname=$dbname", $username, $pw);
 $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

 $sql = "SELECT account_number, branch_name, balance FROM account;";

 $result = $db->query($sql);

 echo("<table border=\"0\" cellspacing=\"5\">\n");
 foreach($result as $row)
 {
 echo("<tr>\n");
 echo("<td>{$row['account_number']}</td>\n");
 echo("<td>{$row['branch_name']}</td>\n");
 echo("<td>{$row['balance']}</td>\n");
 echo("<td><a href=\"balance.php?account_number={$row['account_number']}\">Change
balance</a></td>\n");
 echo("<td><a href=\"deposit.php?account_number={$row['account_number']}\">Deposit</a></td>\n");
 echo("<td><a href=\"withdraw.php?account_number={$row['account_number']}\">Withdraw</a></td>\n");
 echo("</tr>\n");
 }
 echo("</table>\n");

 $db = null;
 }
 catch (PDOException $e)
 {
 echo("<p>ERROR: {$e->getMessage()}</p>");
 }
?>
 </body>
</html>

