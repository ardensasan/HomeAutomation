<?php
include "../databaseConnection.php";
include "../sessions.php";
$year = $_POST['year'];
$kiloWatt = 0;
$query = "SELECT totalConsWatt,TIME_TO_SEC(totalConsDuration) as consDuration FROM tbl_totalconsumption WHERE totalConsYear = ?";
$getTotalConsumption = $conn->prepare($query);
$getTotalConsumption->execute([$year]);
if($getTotalConsumption->rowCount() == 0){
    echo "0";
}else{
    while($totalConsumption = $getTotalConsumption->fetch(PDO::FETCH_ASSOC))
    {
        $kiloWatt += (($totalConsumption['consDuration']*$totalConsumption['totalConsWatt'])/3600)/1000;
    }
}
echo round($kiloWatt,2);
?>