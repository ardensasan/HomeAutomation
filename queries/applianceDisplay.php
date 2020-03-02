<?php 
include "../sessions.php";
include "../databaseConnection.php";
const ADMIN = 0;
$userType = $_SESSION['userType'];
$userID = $_SESSION['userID'];
$displayAppliance = "";
$count = 1;
$query = "SELECT * FROM `tbl_appliances` WHERE `applianceName` IS NOT NULL";
$getApplianceList=$conn->prepare($query);
$getApplianceList->execute();
while($applianceList = $getApplianceList->fetch(PDO::FETCH_ASSOC))
{
$name = $applianceList['applianceName'];
if($applianceList['applianceStatus'] == 0){
$deviceStatus = '<td><h4><span class="badge badge-danger">Turned Off</span></h4></td>
<td><span class="table-remove"><button type="button"
class="btn btn-success btn-rounded btn-sm my-0" onclick="changeApplianceStatus(1,'.$applianceList['applianceID'].',\''.$applianceList['applianceName'].'\',\''.$applianceList['applianceOutputPin'].'\',\''.$userID.'\')">Turn On</button></span>';
if($userType == ADMIN){
$deviceStatus = $deviceStatus.'     <span class="table-remove"><button type="button"
class="btn btn-dark btn-rounded btn-sm my-0" onclick="disableAppliance(2,'.$applianceList['applianceID'].',\''.$applianceList['applianceName'].'\',\''.$applianceList['applianceOutputPin'].'\',\''.$userID.'\')">Disable</button></span>
</td>';
}else{
$deviceStatus = "$deviceStatus"."</td>";
}
}else if($applianceList['applianceStatus'] == 1){
$deviceStatus = '<td><h4><span class="badge badge-success">Turned On</span></h4>
<td><span class="table-remove"><button type="button"
class="btn btn-danger btn-rounded btn-sm my-0" onclick="changeApplianceStatus(0,'.$applianceList['applianceID'].',\''.$applianceList['applianceName'].'\',\''.$applianceList['applianceOutputPin'].'\',\''.$userID.'\')">Turn Off</button></span>';    
if($userType == ADMIN){
$deviceStatus = $deviceStatus.'     <span class="table-remove"><button type="button"
class="btn btn-dark btn-rounded btn-sm my-0" onclick="disableAppliance(2,'.$applianceList['applianceID'].',\''.$applianceList['applianceName'].'\',\''.$applianceList['applianceOutputPin'].'\',\''.$userID.'\')">Disable</button></span>
</td>';
}else{
$deviceStatus = "$deviceStatus"."</td>";
}
}else if($applianceList['applianceStatus'] == 2){
if($userType == ADMIN){
$deviceStatus = '<td><h4><span class="badge badge-dark">Disabled</span></h4></td>
<td><span class="table-remove"><button type="button"
class="btn btn-info btn-rounded btn-sm my-0" onclick="enableAppliance(3,'.$applianceList['applianceID'].',\''.$applianceList['applianceName'].'\',\''.$applianceList['applianceOutputPin'].'\',\''.$userID.'\')">Enable</button></span>
</td>';
}else{
$deviceStatus = '<td><h4><span class="badge badge-dark">Disabled</span></h4></td>
<td>Disabled by Admin</td>';
}
}
if($applianceList['applianceRating'] <= 0){
  $powerConsumption = "Not Calibrated";
}else{
  $powerConsumption = $applianceList['applianceRating']." W";
}
if($userType == 0){
    $deviceStatus .= '<td>
    <a data-toggle="modal" onclick="removeAppliance('.$applianceList['applianceID'].',\''.$applianceList['applianceOutputPin'].'\')"href="#" class="text-danger">
    <i class="fas fa-minus" aria-hidden="true"></i>';
}
if($applianceList['applianceReadingStatus'] == 0){
    $readingStatus = '<h4><span class="badge badge-success">Normal</span></h4>';
}else{
    $readingStatus = '<h4><span class="badge badge-danger">Abnormal</span></h4>';
}
if($applianceList['applianceStatus'] != 1){
  $calibrateStatus = "<td></td>";
}else{
  $calibrateStatus = '<td><button class="btn" title="Calibrate Appliance" onclick="calibrateDisplay('.$applianceList['applianceID'].',\''.$applianceList['applianceName'].'\')"><i class="fas fa-cogs"></i></button></td>';
}
echo '<tr><td>'.$applianceList['applianceID'].'</td>
<td>'.$applianceList['applianceName'].'</td><td><button class="btn" title="Edit Appliance" onclick="editApplianceDisplay('.$applianceList['applianceID'].',\''.$applianceList['applianceName'].'\')"><i class="fas fa-edit"></i></button></td>
<td>'.$powerConsumption.'</td>'.$calibrateStatus.'
<td>'.$readingStatus.'</td>
'.$deviceStatus.'
</td>
</tr>';
}
$conn = null;
// echo $displayAppliance;
?>
