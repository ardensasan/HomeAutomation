<?php
include "../databaseConnection.php";
$applianceID = $_POST['applianceID'];
$readings = array();
$variance = 0.0;
$query = "SELECT *FROM `tbl_readings` WHERE `applianceID` = ? ORDER BY rDateTime DESC LIMIT 20";
$getApplianceReadings = $conn->prepare($query);
$getApplianceReadings->execute([$applianceID]);
while($getReadings = $getApplianceReadings->fetch(PDO::FETCH_ASSOC)){
    $watt = $getReadings['rCurrent'] * $getReadings['rVoltage'];
    array_push($readings,$watt);
}
$num_of_elements = count($readings);
$avgWatt = array_sum($readings) / count($readings);
if($avgWatt > 0){
    $query = "UPDATE `tbl_appliances` SET `applianceRating` = ? WHERE `applianceID` = ?";
    $setApplianceRating = $conn->prepare($query);
    $setApplianceRating->execute([$avgWatt,$applianceID]);
    foreach($readings as $i) 
    { 
        // sum of squares of differences between  
                    // all numbers and means. 
        $variance += pow(($i - $avgWatt), 2); 
    }
    $readings = array();

    $stdDev =  (float)sqrt($variance/$num_of_elements);
    $UCL = $avgWatt + $stdDev*6;
    $LCL = $avgWatt - $stdDev*6;
    $query = "UPDATE `tbl_appliances` SET `applianceUCL` = ?, `applianceLCL` = ? WHERE `applianceID` = ?";
    $setApplianceRating = $conn->prepare($query);
    $setApplianceRating->execute([$UCL,$LCL,$applianceID]);

    $readings = array(
        "avg" => round($avgWatt,2),
        "stdDev" => round($stdDev,2),
        "UCL" => round($UCL,2),
        "LCL" => round($LCL,2)
    );
}else{
    $readings = array(
    "avg" => NULL,
    "stdDev" => NULL,
    "UCL" => NULL,
    "LCL" => NULL
);
}
$conn = null;
echo json_encode($readings); 
    ?>
    
