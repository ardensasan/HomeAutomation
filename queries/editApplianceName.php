<?php
include "../databaseConnection.php";
include "../sessions.php";
$applianceID = $_POST['applianceID'];
$applianceName = $_POST['applianceName'];
//enable actions on appliance
$query = "UPDATE `tbl_appliances` SET `applianceName`= ? WHERE `applianceID` = ?";
$updateAppliance=$conn->prepare($query);
$updateAppliance->execute([$applianceName,$applianceID]);
?>