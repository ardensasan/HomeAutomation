<?php
$url = $_SERVER["REQUEST_URI"]; 
$page = strrpos($url, "index.php");
$url = $_SERVER["REQUEST_URI"]; 
$loginPage = strrpos($url, "loginUser.php");
if (session_status() == PHP_SESSION_NONE) {
    session_start();
    if(isset($_SESSION['userType']) && $page){
        header("Location: dashboard.php");
    }else if(!$loginPage){
        if(!isset($_SESSION['userType']) && !$page ){
            header("Location: index.php");
        }
    }
}
?>