<?php

if (!isset($_SESSION)){
    session_start(); 
}
if(!isset($_SESSION['userType']) || $_SESSION['userType'] == ""){
   header('Location: index.php');
}
?>
