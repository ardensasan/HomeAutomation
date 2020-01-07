<?php
include "../databaseConnection.php";
$rArray = array();
$applianceID = $_POST['applianceID'];
$query = "SELECT applianceID,rCurrent,rVoltage,DATE_FORMAT(rDateTime, '%H:%i:%s') as T FROM `tbl_readings` WHERE `applianceID` = ? ORDER BY `rDateTime` DESC LIMIT 100";
$getApplianceReadings = $conn->prepare($query);
$getApplianceReadings->execute([$applianceID]);
$applianceID = 0;
while($applianceReadings = $getApplianceReadings->fetch(PDO::FETCH_ASSOC))
{
    $watt = $applianceReadings['rVoltage'] * $applianceReadings['rCurrent'];
    array_push($rArray,(array('DT' => $applianceReadings['T'] , 'Watt' => $watt)));
}
//echo json_encode($rArray);
// $result = array(
//     array('y' => '1', 'a' => '1'),
//     array('y' => '1', 'a' => '2'),
//     array('y' => '3', 'a' => '3'),
//     array('y' => '4', 'a' => '4'),
//     array('y' => '5', 'a' => '5'));
echo json_encode(array_reverse($rArray));
?>
