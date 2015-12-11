<?php
require_once('appfunctions.php');

if(is_logged_in()) {
    show_header();
} else {
    redirect_to_login();
}
?>

<!-- Just a template, do actual design -->
<a href="newpage.php"> Insert new page</a> <br/>
<a href="new_registry.php"> Insert new registry</a> <br/>
<a href="view_registry_types.php"> Registry types</a> <br/>
<a href="#"> Insert new fields for registry </a> <br/>
<a href="#"> Remove page</a> <br/>
<a href="#"> Remove registry type</a> <br/>
<a href="insert_registry.php"> Insert new registry</a> <br/>
<a href="viewpage.php"> Open page</a> <br/>
<a href="allpages.php"> Pages</a> <br/>
<a href="newregtype.php"> Insert new registry type</a> <br/>
