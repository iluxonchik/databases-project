<?php
session_start();
if(!isset($_SESSION['userid']) || !isset($_SESSION['username'])) {
    if(isset($_COOKIE['userid']) && isset($_COOKIE['username'])) {
        $_SESSION['userid'] = $_COOKIE['userid'];
        $_SESSION['username'] = $_COOKIE['username'];
    }
}
?>
