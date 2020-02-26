<?php
include "../databaseConnection.php";
include "../sessions.php";
$applianceID = $_POST['applianceID'];
$applianceName = $_POST['applianceName'];
//check for appliance duplicate
$query = "SELECT applianceName FROM tbl_appliances WHERE applianceName = ?";
$checkAppliance=$conn->prepare($query);
$checkAppliance->execute([$applianceName]);
if($checkAppliance->rowCount() > 0){
    echo "0";
}else{
    //add appliance
    $query = "UPDATE `tbl_appliances` SET `applianceName`= ? WHERE `applianceID` = ?";
    $updateAppliance=$conn->prepare($query);
    $updateAppliance->execute([$applianceName,$applianceID]);
    $conn = null;
}
?>