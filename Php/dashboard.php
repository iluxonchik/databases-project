<?php 
require_once('appfunctions.php');

if(is_logged_in()) {
    show_header();
} else {
    redirect_to_login();
}
?>

<!-- Just a template, do actual design -->
<a href="#"> Insert new page</a> <br/>
<a href="#"> Insert new registry</a> <br/>
<a href="#"> Insert new fields for registry </a> <br/>
<a href="#"> Remove page</a> <br/>
<a href="#"> Remove registry type</a> <br/>
<a href="#"> Insert new registry</a> <br/>
<a href="#"> Open page</a> <br/>
