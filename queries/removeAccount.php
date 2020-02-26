<?php
include "../databaseConnection.php";
$userID = $_POST['userID'];
$query = "DELETE FROM `tbl_users` WHERE `userID` = ?";
$deleteAccount=$conn->prepare($query);
$deleteAccount->execute([$userID]);
$conn = null;
?>