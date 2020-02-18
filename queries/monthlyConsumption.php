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
$query = "SELECT totalConsMonth,totalConsWatt,TIME_TO_SEC(totalConsDuration) as consDuration FROM tbl_totalconsumption WHERE totalConsYear = ?";
$getTotalConsumption = $conn->prepare($query);
$getTotalConsumption->execute([$year]);
while($totalConsumption = $getTotalConsumption->fetch(PDO::FETCH_ASSOC))
{
    if($totalConsumption['totalConsMonth'] == 1){
        $jan += (($totalConsumption['consDuration']*($totalConsumption['totalConsWatt']))/3600)/1000;
    }else if($totalConsumption['totalConsMonth'] == 2){
        $feb += (($totalConsumption['consDuration']*($totalConsumption['totalConsWatt']))/3600)/1000;
    }else if($totalConsumption['totalConsMonth'] == 3){
        $mar += (($totalConsumption['consDuration']*($totalConsumption['totalConsWatt']))/3600)/1000;
    }else if($totalConsumption['totalConsMonth'] == 4){
        $apr += (($totalConsumption['consDuration']*($totalConsumption['totalConsWatt']))/3600)/1000;
    }else if($totalConsumption['totalConsMonth'] == 5){
        $may += (($totalConsumption['consDuration']*($totalConsumption['totalConsWatt']))/3600)/1000;
    }else if($totalConsumption['totalConsMonth'] == 6){
        $jun += (($totalConsumption['consDuration']*($totalConsumption['totalConsWatt']))/3600)/1000;
    }else if($totalConsumption['totalConsMonth'] == 7){
        $jul += (($totalConsumption['consDuration']*($totalConsumption['totalConsWatt']))/3600)/1000;
    }else if($totalConsumption['totalConsMonth'] == 8){
        $aug += (($totalConsumption['consDuration']*($totalConsumption['totalConsWatt']))/3600)/1000;
    }else if($totalConsumption['totalConsMonth'] == 9){
        $sep += (($totalConsumption['consDuration']*($totalConsumption['totalConsWatt']))/3600)/1000;
    }else if($totalConsumption['totalConsMonth'] == 10){
        $oct += (($totalConsumption['consDuration']*($totalConsumption['totalConsWatt']))/3600)/1000;
    }else if($totalConsumption['totalConsMonth'] == 11){
        $nov += (($totalConsumption['consDuration']*($totalConsumption['totalConsWatt'])/3600))/1000;
    }else if($totalConsumption['totalConsMonth'] == 12){
        $dec += (($totalConsumption['consDuration']*($totalConsumption['totalConsWatt'])/3600))/1000;
    }
}
array_push($result,(array('Month' => "January", 'KWh' => $jan)));
array_push($result,(array('Month' => "February", 'KWh' => $feb)));
array_push($result,(array('Month' => "March", 'KWh' => $mar)));
array_push($result,(array('Month' => "April", 'KWh' => $apr)));
array_push($result,(array('Month' => "May", 'KWh' => $may)));
array_push($result,(array('Month' => "June", 'KWh' => $jun)));
array_push($result,(array('Month' => "July", 'KWh' => $jul)));
array_push($result,(array('Month' => "August", 'KWh' => $aug)));
array_push($result,(array('Month' => "September", 'KWh' => $sep)));
array_push($result,(array('Month' => "October", 'KWh' => $oct)));
array_push($result,(array('Month' => "November", 'KWh' => $nov)));
array_push($result,(array('Month' => "December", 'KWh' => $dec)));
echo json_encode($result);
?>