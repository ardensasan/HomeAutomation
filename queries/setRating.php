<?php
include "../databaseConnection.php";
$applianceID = $_POST['applianceID'];
$readings = array();
$query = "SELECT *FROM `tbl_readings` WHERE `applianceID` = ? ORDER BY rDateTime DESC LIMIT 50 ";
$getApplianceReadings = $conn->prepare($query);
$getApplianceReadings->execute([$applianceID]);
while($getReadings = $getApplianceReadings->fetch(PDO::FETCH_ASSOC)){
    $watt = $getReadings['rCurrent'] * $getReadings['rVoltage'];
    array_push($readings,$watt);
}
$avgWatt = array_sum($readings) / count($readings);
echo $avgWatt;
?>