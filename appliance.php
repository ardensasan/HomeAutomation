<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js" lang="en">
  <!--<![endif]-->
  <body>
    <?php 
include "sessions.php";
include "databaseConnection.php";
include_once "navigator.php";
$userType = $_SESSION['userType'];
$userID = $_SESSION['userID'];
$currentPage = basename($_SERVER['PHP_SELF']);
?>
    <div class="dashboard-wrapper">
      <div class="container-fluid dashboard-content ">
        <div class="card">
          <div class="card-body">
            <table class="table table-bordered table-responsive-md table-striped text-center">
              <thead>
                <col width="60">
                <col width="250">
                <col width="90">
                <col width="60">
                <col width="200">
                <tr>
                  <th class="text-center">Number
                  </th>
                  <th class="text-center">Appliance Name
                  </th>
                  <th colspan="2" class="text-center">Average Power
                  </th>
                  <th class="text-center">Status
                  </th>
                  <th class="text-center">Actions
                  </th>
                </tr>
              </thead>
              <tbody>
                <?php 
$count = 1;
$query = "SELECT * FROM `tbl_appliances`";
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
  $powerConsumption = "NC";
}else{
  $powerConsumption = $applianceList['applianceRating']." W";
}
echo '<tr><td>'.$applianceList['applianceID'].'</td>
<td>'.$applianceList['applianceName'].'</td>
<td>'.$powerConsumption.'</td><td><button class="btn" onclick="calibrateDisplay('.$applianceList['applianceID'].',\''.$applianceList['applianceName'].'\')"><i title="Calibrate" class="fas fa-cogs"></i></button></td>
'.$deviceStatus.'
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
       <h4>Calibrate <span id="calAppliance"></span></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
  
      </div>
      <div class="modal-body">
        <p><span id="calMessage">Turn On Appliance Before Calibrating</span></p>
      <div class="modal-body" id="calibrateCountdown">
        <button class="btn btn-primary" onclick="calibrateCount()"><span id="calText"></span></button>
    </div>

  </div>
</div>
<!-- end calibrate modal -->
      <!-- edit schedule modal -->
      <div class="modal fade" id="editApplianceModal" role="dialog">
        <div class="modal-dialog">
          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Edit Appliance
              </h4>
              <button type="button" class="close" data-dismiss="modal">&times;
              </button>
            </div>
            <div class="modal-body">
              <input type="text" class="form-control" id="applianceName" placeholder="">
              <input type="hidden" value="">
            </div>
            <button type="button" id="applianceID" value="" class="btn btn-default" data-dismiss="modal" onclick = "changeApplianceName(this.value,document.getElementById('applianceName').placeholder)">Save
            </button>
          </div>
        </div>
      </div>
      </body>
    </html>
