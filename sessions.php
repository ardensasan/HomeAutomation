<?php
$url = $_SERVER["REQUEST_URI"]; 
$page = strrpos($url, "index.php"); 
if (!isset($_SESSION)){
    session_start(); 
}
if(!isset($_SESSION['userType']) || $_SESSION['userType'] == ""){
if(!$page){
    header('Location: index.php');
}
}
?>
