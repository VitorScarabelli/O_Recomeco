<?php
session_start();
if(!isset($_SESSION['autorizacao']) || $_SESSION['autorizacao'] !== true){
    unset($_SESSION['autorizacao']);
    session_destroy();
    header('Location: ../login/login.php');
    exit(); // sempre bom depois de header
}
