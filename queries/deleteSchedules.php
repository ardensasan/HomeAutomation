<?php
include "../databaseConnection.php";
$applianceID = $_POST['applianceID'];
$query = "DELETE FROM `tbl_schedules` WHERE `scheduleApplianceID` = ?";
$updateAppliance=$conn->prepare($query);
$updateAppliance->execute([$applianceID]);
?>