<html>
<div id="timestamp"></div>
<meta http-equiv="refresh" content = "2" />
</html>

<?php
$status = 0;
include "databaseConnection.php";
date_default_timezone_set('Asia/Manila');
$date = date('Y-m-d h:i:s');
$timestamp = strtotime($date);
// Subtract time from datetime
$time = $timestamp - (5 * 60);
// Date and time after subtraction
$datetime = date('Y-m-d h:i:s', $time);
echo $datetime;
$query = "SELECT `applianceStatus` FROM `tbl_appliances` WHERE `applianceID` = '1'";
$getApplianceStatus = $conn->prepare($query);
$getApplianceStatus->execute();
while($applianceStatus = $getApplianceStatus->fetch(PDO::FETCH_ASSOC)){
    $status = $applianceStatus['applianceStatus'];
}

$query = "INSERT INTO `tbl_readings` (applianceID,rCurrent,rVoltage,rDateTime) VALUES (?,?,?,?)";
$addReadigns= $conn->prepare($query);
$addReadigns->execute([1,rand(-10,10),rand(-10,20),$date]);

$query = "INSERT INTO `tbl_readings` (applianceID,rCurrent,rVoltage,rDateTime) VALUES (?,?,?,?)";
$addReadigns= $conn->prepare($query);
$addReadigns->execute([2,rand(-10,10),rand(-10,20),$date]);

$query = "INSERT INTO `tbl_readings` (applianceID,rCurrent,rVoltage,rDateTime) VALUES (?,?,?,?)";
$addReadigns= $conn->prepare($query);
$addReadigns->execute([3,rand(-10,10),rand(-10,20),$date]);

$query = "INSERT INTO `tbl_readings` (applianceID,rCurrent,rVoltage,rDateTime) VALUES (?,?,?,?)";
$addReadigns= $conn->prepare($query);
$addReadigns->execute([4,rand(-10,10),rand(-10,20),$date]);

$query = "DELETE FROM `tbl_readings` WHERE rDateTime < ?";
$deleteReadings = $conn->prepare($query);
$deleteReadings->execute([$datetime]);
echo $status;
?>