<?php
include "../databaseConnection.php";
$scheduleID = $_POST['scheduleID'];
$scheduleDate = $_POST['scheduleDate'];
$scheduleTime = $_POST['scheduleTime'];
$scheduleApplianceID = $_POST['scheduleApplianceID'];
$scheduleAction = $_POST['scheduleAction'];
$scheduleRepeat = $_POST['scheduleRepeat'];
$query = "UPDATE `tbl_schedules` SET scheduleDate =STR_TO_DATE(? , '%m/%d/%Y'), scheduleTime=STR_TO_DATE(?, '%l:%i %p' ), scheduleApplianceID=?, scheduleAction =?, scheduleRepeat=?, isExecuted =? WHERE scheduleID =?" ;
$updateSchedule=$conn->prepare($query);
$updateSchedule->execute([$scheduleDate,$scheduleTime,$scheduleApplianceID,$scheduleAction,$scheduleRepeat,0,$scheduleID]);
$conn = null;
?>
