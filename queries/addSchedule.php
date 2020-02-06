<?php
include "../databaseConnection.php";
$scheduleDate = $_POST['scheduleDate'];
$scheduleTime = $_POST['scheduleTime'];
$scheduleApplianceID = $_POST['scheduleApplianceID'];
$scheduleAction = $_POST['scheduleAction'];
$scheduleRepeat = $_POST['scheduleRepeat'];
$query = "INSERT INTO `tbl_schedules`(`scheduleDate`,`scheduleTime`,`scheduleApplianceID`,`scheduleAction`,`scheduleRepeat`) 
VALUES(STR_TO_DATE('$scheduleDate' , '%m/%d/%Y'),STR_TO_DATE('$scheduleTime', '%l:%i %p' ),?,?,?)";
$deleteSchedule=$conn->prepare($query);
$deleteSchedule->execute([$scheduleApplianceID,$scheduleAction,$scheduleRepeat]);
?>