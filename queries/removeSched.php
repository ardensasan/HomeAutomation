<?php
include "../databaseConnection.php";
$scheduleID = $_POST['scheduleID'];
$query = "DELETE FROM `tbl_schedules` WHERE `scheduleID`= ?";
$deleteSchedule=$conn->prepare($query);
$deleteSchedule->execute([$scheduleID]);
$conn = null;
?>
