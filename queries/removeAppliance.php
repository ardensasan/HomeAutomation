<?php
include "../databaseConnection.php";
include "../sessions.php";
$applianceID = $_POST['applianceID'];
$applianceName = $_POST['applianceName'];
$applianceOutputPin = $_POST['applianceOutputPin'];
//enable actions on appliance
$query = "UPDATE `tbl_appliances` SET `applianceName`= ?,`applianceStatus` = ?,`applianceRating` = ?,`applianceUCL` =?,`applianceLCL` = ?,`applianceReadingStatus` = ? WHERE `applianceID` = ?";
$updateAppliance=$conn->prepare($query);
$updateAppliance->execute([NULL,0,NULL,NULL,NULL,0,$applianceID]);
//delete schedules associated with appliance
$query = "DELETE FROM `tbl_schedules` WHERE `scheduleApplianceID` = ?";
$updateAppliance=$conn->prepare($query);
$updateAppliance->execute([$applianceID]);
exec("sudo python /var/www/html/scripts/turnOFF.py $applianceOutputPin");
?>
