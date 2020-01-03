<?php
$host='localhost';
$db = 'homeautomation';
$username = "root";
$password = "";
$dsn= "mysql:host=$host;dbname=$db";
 
try{
 // create a PDO connection with the configuration data
 $conn = new PDO($dsn, $username, $password);
}catch (PDOException $e){
 // report error message
 echo $e->getMessage();
}
?>
