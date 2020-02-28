<?php
include "../databaseConnection.php";
include "../sessions.php";
$applianceID = $_POST['applianceID'];
$query = "SELECT * FROM `tbl_appliances` WHERE `applianceName` IS NULL OR `applianceID` = ?";
$getApplianceList=$conn->prepare($query);
$getApplianceList->execute([$applianceID]);
while($applianceList = $getApplianceList->fetch(PDO::FETCH_ASSOC))
{
    echo '<option id="'.$applianceList['applianceID'].'" value="'.$applianceList['applianceID'].'">'.$applianceList['applianceID'].'</option>';
    }
$conn = null;    
    ?>
