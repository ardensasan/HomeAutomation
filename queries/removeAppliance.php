<?php
include "../databaseConnection.php";
include "../sessions.php";
$applianceID = $_POST['applianceID'];
$applianceName = $_POST['applianceName'];
//enable actions on appliance
$query = "UPDATE `tbl_appliances` SET `applianceName`= ?,`applianceStatus` = ?,`applianceRating` = ? WHERE `applianceID` = ?";
$updateAppliance=$conn->prepare($query);
$updateAppliance->execute([NULL,NULL,0,$applianceID]);
//delete schedules associated with appliance
$query = "DELETE FROM `tbl_schedules` WHERE `scheduleApplianceID` = ?";
$updateAppliance=$conn->prepare($query);
$updateAppliance->execute([$applianceID]);
?>