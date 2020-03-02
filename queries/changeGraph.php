<?php
include "../databaseConnection.php";
$result = array();
$applianceID = $_POST['applianceID'];
$query = "SELECT tbl_appliances.applianceName,tbl_appliances.applianceStatus, tbl_readings.rVoltage, tbl_readings.rCurrent,tbl_readings.rDateTime
FROM tbl_appliances
JOIN tbl_readings ON tbl_appliances.applianceID=tbl_readings.applianceID
WHERE tbl_appliances.applianceID = ? ORDER BY tbl_readings.rDateTime DESC LIMIT 1";

$getApplianceDetails = $conn->prepare($query);
$getApplianceDetails->execute([$applianceID]);
while($applianceDetails = $getApplianceDetails->fetch(PDO::FETCH_ASSOC))
{
    $result = array(
        "applianceName" => $applianceDetails['applianceName'],
        "applianceStatus" => $applianceDetails['applianceStatus'],
        "voltage" => $applianceDetails['rVoltage'],
        "current" => $applianceDetails['rCurrent']
    );
}
$conn = null;
echo json_encode($result);

?>
