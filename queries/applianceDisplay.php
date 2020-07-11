<?php 
include "../sessions.php";
include "../databaseConnection.php";
const ADMIN = 0;
$userType = $_SESSION['userType'];
$userID = $_SESSION['userID'];
$displayAppliance = "";
$count = 1;
$query = "SELECT tbl_appliances.applianceID,tbl_appliances.applianceName,tbl_appliances.applianceRating,tbl_appliances.applianceStatus, tbl_appliances.applianceOutputPin,tbl_appliances.applianceUCL,tbl_readings.rDateTime,tbl_appliances.applianceLCL,(tbl_readings.rCurrent*tbl_readings.rVoltage) as Watt FROM tbl_appliances INNER JOIN tbl_readings ON tbl_appliances.applianceID=tbl_readings.applianceID WHERE tbl_readings.rDateTime = ( SELECT MAX(rDateTime) FROM tbl_readings WHERE tbl_readings.applianceID = tbl_appliances.applianceID)";
$getApplianceList=$conn->prepare($query);
$getApplianceList->execute();
while($applianceList = $getApplianceList->fetch(PDO::FETCH_ASSOC))
{
$controlLimit = "";
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
  $powerConsumption = "Not Set";
}else{
  $powerConsumption = $applianceList['applianceRating']." W";
}
if($userType == 0){
    $deviceStatus .= '<td>
    <a data-toggle="modal" onclick="removeAppliance('.$applianceList['applianceID'].',\''.$applianceList['applianceOutputPin'].'\')"href="#" class="text-danger">
    <i class="fas fa-minus" aria-hidden="true"></i>';
}
if($applianceList['applianceStatus'] = 1 and $applianceList['Watt'] > 0){
  $calibrateStatus = '<td><button class="btn" title="Fault Learning" onclick="calibrateDisplay('.$applianceList['applianceID'].',\''.$applianceList['applianceName'].'\')"><i class="fas fa-cogs"></i></button></td>';
}else{
  $calibrateStatus = '<td><button class="btn" title="Fault Learning" disabled onclick="calibrateDisplay('.$applianceList['applianceID'].',\''.$applianceList['applianceName'].'\')"><i class="fas fa-cogs"></i></button></td>';
}
if($applianceList['applianceUCL']  == NULL){
  $controlLimit .= "<td>Not Set</td>";
}else{
  $controlLimit .= "<td>".$applianceList['applianceUCL']." W</td>";
}
if($applianceList['applianceLCL']  == NULL){
  $controlLimit .= "<td>Not Set</td>";
}else{
  $controlLimit .= "<td>".$applianceList['applianceLCL']." W</td>";
}
echo '<tr><td>'.$applianceList['applianceID'].'</td>
<td>'.$applianceList['applianceName'].'</td><td><button class="btn" title="Edit Appliance" onclick="editApplianceDisplay('.$applianceList['applianceID'].',\''.$applianceList['applianceName'].'\')"><i class="fas fa-edit"></i></button></td>
<td>'.$powerConsumption.'</td>'.$calibrateStatus.'
'.$controlLimit.'
'.$deviceStatus.'
</td>
</tr>';
}
$conn = null;
// echo $displayAppliance;
?>
