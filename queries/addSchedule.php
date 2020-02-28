<?php
include "../databaseConnection.php";
$scheduleDate = $_POST['scheduleDate'];
$scheduleTime = $_POST['scheduleTime'];
$scheduleApplianceID = $_POST['scheduleApplianceID'];
$scheduleAction = $_POST['scheduleAction'];
$scheduleRepeat = $_POST['scheduleRepeat'];
$query = "INSERT INTO `tbl_schedules`(`scheduleDate`,`scheduleTime`,`scheduleApplianceID`,`scheduleAction`,`scheduleRepeat`,`isExecuted`) 
VALUES(STR_TO_DATE('$scheduleDate' , '%m/%d/%Y'),STR_TO_DATE('$scheduleTime', '%l:%i %p' ),?,?,?,?)";
$addSchedule=$conn->prepare($query);
$addSchedule->execute([$scheduleApplianceID,$scheduleAction,$scheduleRepeat,0]);
$conn = null;
?>
