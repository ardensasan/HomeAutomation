<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js" lang="en">
<!--<![endif]-->

<body>
<?php 
include_once "navigator.php";
$userType = $_SESSION['userType'];
$userID = $_SESSION['userID'];
$currentPage = basename($_SERVER['PHP_SELF']);
?>
        <div class="dashboard-wrapper">
            <div class="container-fluid dashboard-content ">
                <div class="card">
                    <div class="card-body">
                        <?php
$query = "SELECT * FROM `tbl_appliances` WHERE `applianceName` IS NULL";
$getApplianceList=$conn->prepare($query);
$getApplianceList->execute();
if($getApplianceList->rowCount() > 0){
  echo '  <span class="table-add float-left mb-3 mr-2">
  <a data-toggle="modal" href="#addApplianceModal" class="text-success">
    <i
       class="fas fa-plus" aria-hidden="true">
    </i>
  </a>  Add Appliance
</span>';
}?>
                            <table class="table table-bordered table-responsive-md table-striped text-center">
                                <thead>
                                    <col width="20">
                                        <col width="300">
                                            <col width="100">
                                                <col width="100">
                                                    <col width="50">
                                                        <col width="100">
                                                        <col width="100">
                                                        <col width="150">
                                                        <col width="200">
                                                            <tr>
                                                                <th class="text-center">Port
                                                                </th>
                                                                <th class="text-center" colspan="2">Appliance Name
                                                                </th>
                                                                <th colspan="2" class="text-center">Average Power
                                                                </th>
                                                                <th class="text-center">Upper Control Limit
                                                                </th>
                                                                <th class="text-center">Lower Control Limit
                                                                </th>
                                                                <th class="text-center">Status
                                                                </th>
                                                                <th class="text-center">Actions
                                                                </th>
                                                                <?php
                                                                if($userType == 0){
                                                                    echo '<th class="text-center">Remove</th>';
                                                                }?>
                                                            </tr>
                                </thead>
                                <tbody id="applianceDisplay">
                                <?php 
$count = 1;
$query = "SELECT tbl_appliances.applianceID,tbl_appliances.applianceName,tbl_appliances.applianceRating,tbl_appliances.applianceStatus, tbl_appliances.applianceOutputPin,tbl_appliances.applianceUCL,tbl_readings.rDateTime,tbl_appliances.applianceLCL,(tbl_readings.rCurrent*tbl_readings.rVoltage) as Watt FROM tbl_appliances INNER JOIN tbl_readings ON tbl_appliances.applianceID=tbl_readings.applianceID WHERE tbl_readings.rDateTime = ( SELECT MAX(rDateTime) FROM tbl_readings WHERE tbl_readings.applianceID = tbl_appliances.applianceID) AND tbl_appliances.applianceName IS NOT NULL";
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
if($userType == ADMIN){
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
?>
                                </tbody>
                            </table>
                    </div>
                </div>
            </div>
            <!-- calibrate modal -->
            <div id="calibrateModal" class="modal fade bd-example-modal-sm" role="dialog">
                <div class="modal-dialog modal-sm">

                    <!-- Modal content-->
                    <input type="hidden" value="" id="calAppID">
                    <input type="hidden" value="" id="calAppName">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4>Teach <span id="calAppliance"></span></h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>

                        </div>
                        <div class="modal-body">
                            <p><span id="calMessage">Dont Turn Off Appliance While Teaching</span></p>
                            <div class="modal-body" id="calibrateCountdown">
                                <button class="btn btn-primary" onclick="calibrateCount()"><span id="calText"></span></button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <!-- end calibrate modal -->
            <!-- edit appliance modal -->
            <div id="editApplianceModal" class="modal fade bd-example-modal-sm" role="dialog">
                <div class="modal-dialog modal-sm">
                    <!-- Modal content-->
                    <input type="hidden" value="" id="editAppID">
                    <input type="hidden" value="" id="editAppName">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4>Edit Appliance<span id="calAppliance"></span></h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <div class="input-group input-group-round">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                        <i class="fas fa-plug">
                        </i>
                      </span>
                                    </div>
                                    <input type="text" class="form-control filter-list-input" maxlength="20" id="editedApplianceName" value="" placeholder="Enter Appliance Name">
                                </div>
                                </div>
                                <div class="form-group">
                            <label>Port</label>
                                <div class="input-group input-group-round">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                        <i class="fas fa-plug">
                        </i>
                      </span>
                                    </div>
                                        <select class="form-control" id="editPortNum">
                                    <div id="portOptions">
                                      </select>
                            </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary" onclick="editApplianceName()">Confirm</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end edit appliance modal -->
                        <!-- edit appliance modal -->
                        <div id="addApplianceModal" class="modal fade bd-example-modal-sm" role="dialog">
                <div class="modal-dialog modal-sm">

                    <!-- Modal content-->
                    <input type="hidden" value="" id="editAppID">
                    <input type="hidden" value="" id="editAppName">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4>Add Appliance</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                            <label>Name</label>
                                <div class="input-group input-group-round">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                        <i class="fab fa-medapps">
                        </i>
                      </span>
                                    </div>
                                    <input type="text" class="form-control filter-list-input" maxlength="20" id="addApplianceName" value="" placeholder="Appliance Name">
                                </div>
                            </div>
                            <div class="form-group">
                            <label>Port</label>
                                <div class="input-group input-group-round">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                        <i class="fas fa-plug">
                        </i>
                      </span>
                                    </div>
                                        <select class="form-control" id="portNum">
                                    <?php
                                    $query = "SELECT * FROM `tbl_appliances` WHERE `applianceName` IS NULL";
                                    $getApplianceList=$conn->prepare($query);
                                    $getApplianceList->execute();
                                      while($applianceList = $getApplianceList->fetch(PDO::FETCH_ASSOC))
                                      {
                                          echo '<option value="'.$applianceList['applianceID'].'">'.$applianceList['applianceID'].'</option>';
                                      }?>
                                      </select>
                                    </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary" onclick="addAppliance()">Confirm</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end edit appliance modal -->
            <script>
var myVar = setInterval(refreshTable, 1000);

function refreshTable() {
    refreshAppliancePage();
}
</script>
</body>
</html>
