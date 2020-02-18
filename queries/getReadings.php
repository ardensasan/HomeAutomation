<?php
include "../databaseConnection.php";
$rArray = array();
$applianceID = $_POST['applianceID'];
$limit = $_POST['limit'];
$query = "SELECT applianceID,rCurrent,rVoltage,DATE_FORMAT(rDateTime, '%H:%i:%s') as T FROM `tbl_readings` WHERE `applianceID` = ? ORDER BY `rDateTime` DESC LIMIT $limit";
$getApplianceReadings = $conn->prepare($query);
$getApplianceReadings->execute([$applianceID]);
$applianceID = 0;
while($applianceReadings = $getApplianceReadings->fetch(PDO::FETCH_ASSOC))
{
    $watt = $applianceReadings['rVoltage'] * $applianceReadings['rCurrent'];
    array_push($rArray,(array('DT' => $applianceReadings['T'] , 'Watt' => $watt)));
}
echo json_encode(array_reverse($rArray));
?>
