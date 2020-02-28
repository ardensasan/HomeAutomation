<?php
include "../databaseConnection.php";
include "../sessions.php";
$applianceID = $_POST['applianceID'];
$applianceName = $_POST['applianceName'];
$appliancePort = $_POST['appliancePort'];
//enable actions on appliance
$query = "UPDATE `tbl_appliances` SET `applianceName`= ?,`applianceRating` = ?,`applianceUCL` = ?,`applianceLCL` = ? WHERE `applianceID` = ?";
$updateAppliance=$conn->prepare($query);
$updateAppliance->execute([NULL,NULL,NULL,NULL,$applianceID]);

$query = "UPDATE `tbl_appliances` SET `applianceName`= ? WHERE `applianceID` = ?";
$updateAppliance=$conn->prepare($query);
$updateAppliance->execute([$applianceName,$appliancePort]);
$conn = null;
?>
