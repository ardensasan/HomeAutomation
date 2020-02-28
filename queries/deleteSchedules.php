<?php
include "../databaseConnection.php";
$applianceID = $_POST['applianceID'];
$query = "DELETE FROM `tbl_schedules` WHERE `scheduleApplianceID` = ?";
$deleteSchedule=$conn->prepare($query);
$deleteSchedule->execute([$applianceID]);
$conn = null;
?>
