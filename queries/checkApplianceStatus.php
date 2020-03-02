<?php 
include "../sessions.php";
include "../databaseConnection.php";
$applianceID = $_POST['applianceID'];
$query = "SELECT applianceStatus FROM tbl_appliances WHERE applianceID = ?";
$getApplianceStatus=$conn->prepare($query);
$getApplianceStatus->execute([$applianceID]);
while($applianceStatus = $getApplianceStatus->fetch(PDO::FETCH_ASSOC))
{
    $status = $applianceStatus['applianceStatus'];
}
$conn = null;
echo $status;