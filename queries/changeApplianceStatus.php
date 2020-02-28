<?php
include "../databaseConnection.php";
include "../sessions.php";
$userID = $_POST['userID'];
$applianceID = $_POST['applianceID'];
$action = $_POST['action'];
$applianceName = $_POST['applianceName'];
$applianceOutputPin = $_POST['applianceOutputPin'];
$query = "UPDATE tbl_appliances SET `applianceStatus`= ? WHERE `applianceID` = ?";
$updateAppliance=$conn->prepare($query);
$updateAppliance->execute([$action,$applianceID]);
//add logs to database
date_default_timezone_set('Asia/Manila');
$date = date('Y-m-d H:i:s');
$query = "INSERT INTO tbl_logs (logDateTime, logAppliance, logAction, logVia,logUser) VALUES (?,?,?,?,?)";
$addLog = $conn->prepare($query);
$addLog->execute([$date,$applianceName,$action,0,$userID]);
$conn = null;
//execute python script
if($action == 1){
    exec("sudo python /var/www/html/scripts/turnON.py $applianceOutputPin");
}else if($action == 0){
    exec("sudo python /var/www/html/scripts/turnOFF.py $applianceOutputPin");
}
