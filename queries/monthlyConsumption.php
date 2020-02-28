<?php
include "../databaseConnection.php";
include "../sessions.php";
$year = $_POST['year'];
$jan = 0;
$feb = 0;
$mar = 0;
$apr = 0;
$may = 0;
$jun = 0;
$jul = 0;
$aug = 0;
$sep = 0;
$oct = 0;
$nov = 0;
$dec = 0;
$result = array();
$query = "SELECT MONTH(totalConsStart) as totalConsMonth,(totalConsWatt/1000) as totalConsWatt,timestampdiff(second,totalConsStart, totalConsEnd)/3600 as consDuration FROM tbl_totalconsumption WHERE year(totalConsStart) = ?";
$getTotalConsumption = $conn->prepare($query);
$getTotalConsumption->execute([$year]);
while($totalConsumption = $getTotalConsumption->fetch(PDO::FETCH_ASSOC))
{
    if($totalConsumption['totalConsMonth'] == 1){
        $jan += $totalConsumption['consDuration']*$totalConsumption['totalConsWatt'];
    }else if($totalConsumption['totalConsMonth'] == 2){
        $feb += $totalConsumption['consDuration']*$totalConsumption['totalConsWatt'];
    }else if($totalConsumption['totalConsMonth'] == 3){
        $mar += $totalConsumption['consDuration']*$totalConsumption['totalConsWatt'];
    }else if($totalConsumption['totalConsMonth'] == 4){
        $apr += $totalConsumption['consDuration']*$totalConsumption['totalConsWatt'];
    }else if($totalConsumption['totalConsMonth'] == 5){
        $may += $totalConsumption['consDuration']*$totalConsumption['totalConsWatt'];
    }else if($totalConsumption['totalConsMonth'] == 6){
        $jun += $totalConsumption['consDuration']*$totalConsumption['totalConsWatt'];
    }else if($totalConsumption['totalConsMonth'] == 7){
        $jul += $totalConsumption['consDuration']*$totalConsumption['totalConsWatt'];
    }else if($totalConsumption['totalConsMonth'] == 8){
        $aug += $totalConsumption['consDuration']*$totalConsumption['totalConsWatt'];
    }else if($totalConsumption['totalConsMonth'] == 9){
        $sep += $totalConsumption['consDuration']*$totalConsumption['totalConsWatt'];
    }else if($totalConsumption['totalConsMonth'] == 10){
        $oct += $totalConsumption['consDuration']*$totalConsumption['totalConsWatt'];
    }else if($totalConsumption['totalConsMonth'] == 11){
        $nov += $totalConsumption['consDuration']*$totalConsumption['totalConsWatt'];
    }else if($totalConsumption['totalConsMonth'] == 12){
        $dec += $totalConsumption['consDuration']*$totalConsumption['totalConsWatt'];
    }
}
array_push($result,(array('Month' => "January", 'KWh' => round($jan,2))));
array_push($result,(array('Month' => "February", 'KWh' => round($feb,2))));
array_push($result,(array('Month' => "March", 'KWh' => round($mar,2))));
array_push($result,(array('Month' => "April", 'KWh' => round($apr,2))));
array_push($result,(array('Month' => "May", 'KWh' => round($may,2))));
array_push($result,(array('Month' => "June", 'KWh' => round($jun,2))));
array_push($result,(array('Month' => "July", 'KWh' => round($jul,2))));
array_push($result,(array('Month' => "August", 'KWh' => round($aug,2))));
array_push($result,(array('Month' => "September", 'KWh' => round($sep,2))));
array_push($result,(array('Month' => "October", 'KWh' => round($oct,2))));
array_push($result,(array('Month' => "November", 'KWh' => round($nov,2))));
array_push($result,(array('Month' => "December", 'KWh' => round($dec,2))));
$conn = null;
echo json_encode($result);
?>
