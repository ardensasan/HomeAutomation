<?php
include "../databaseConnection.php";
$query = "TRUNCATE `tbl_logs`"; 
$clearLogs = $conn->prepare($query);
$clearLogs->execute();
$conn = null;
?>
