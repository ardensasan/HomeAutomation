<?php
include "../databaseConnection.php";
include "../sessions.php";
$year = $_POST['year'];
$kiloWatt = 0;
$query = "SELECT totalConsWatt,timestampdiff(second,totalConsStart, totalConsEnd)/3600 as H FROM tbl_totalconsumption WHERE year(totalConsStart) = ?";
$getTotalConsumption = $conn->prepare($query);
$getTotalConsumption->execute([$year]);
if($getTotalConsumption->rowCount() == 0){
    echo "0";
}else{
    while($totalConsumption = $getTotalConsumption->fetch(PDO::FETCH_ASSOC))
    {
        $kiloWatt += ($totalConsumption['H']/1000)*$totalConsumption['totalConsWatt'];
    }
}
$conn = null;
echo round($kiloWatt,2);
?>
