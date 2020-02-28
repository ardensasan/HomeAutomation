<?php
include "../databaseConnection.php";
include "../sessions.php";
$userID = $_POST['userID'];
$applianceID = $_POST['applianceID'];
$applianceName = $_POST['applianceName'];
$action = $_POST['action'];
$applianceOutputPin = $_POST['applianceOutputPin'];
//enable actions on appliance
$query = "UPDATE `tbl_appliances` SET `applianceStatus`= ? WHERE `applianceID` = ?";
$updateAppliance=$conn->prepare($query);
$updateAppliance->execute(['0',$applianceID]);
//add logs to database
date_default_timezone_set('Asia/Manila');
$date = date('Y-m-d H:i:s');
$query = "INSERT INTO tbl_logs (logDateTime, logAppliance, logAction, logVia,logUser) VALUES (?,?,?,?,?)";
$addLog = $conn->prepare($query);
$addLog->execute([$date,$applianceName,$action,0,$userID]);
$conn = null;
exec("sudo python /var/www/html/scripts/turnOFF.py $applianceOutputPin");
?>
