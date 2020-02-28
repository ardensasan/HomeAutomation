<?php
include "../databaseConnection.php";
$scheduleTime = $_POST['scheduleTime'];
$scheduleApplianceID = $_POST['scheduleApplianceID'];
$scheduleAction = $_POST['scheduleAction'];
$scheduleRepeat = $_POST['scheduleRepeat'];
$query = "INSERT INTO `tbl_schedules`(`scheduleTime`,`scheduleApplianceID`,`scheduleAction`,`scheduleRepeat`,`isExecuted`) 
VALUES(STR_TO_DATE('$scheduleTime', '%l:%i %p' ),?,?,?,?)";
$deleteSchedule=$conn->prepare($query);
$deleteSchedule->execute([$scheduleApplianceID,$scheduleAction,$scheduleRepeat,0]);
$conn = null;
?>
