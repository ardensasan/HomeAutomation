<?php
include "../databaseConnection.php";
$applianceID = $_POST['applianceID'];
$applianceName = $_POST['applianceName'];
$query = "SELECT `applianceName` FROM `tbl_appliances` WHERE `applianceName`= ?";
$checkDuplicate=$conn->prepare($query);
$checkDuplicate->execute([$applianceName]);
if($checkDuplicate->rowCount() == 0){
    $query = "UPDATE `tbl_appliances` SET `applianceName`= ?  WHERE `applianceID` = ?";
    $updateAppliance=$conn->prepare($query);
    $updateAppliance->execute([$applianceName,$applianceID]);
    $conn = null;
}else{
    echo "1";
}
?>
