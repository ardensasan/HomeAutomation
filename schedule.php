<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js" lang="en">
  <link rel="stylesheet" href="assets/vendor/datepicker/tempusdominus-bootstrap-4.css" />
  <!--<![endif]-->
  <body>
    <?php 
    include "sessions.php";
include_once "navigator.php";
$month = date('m');
$day = date('d');
$year = date('Y');
$date = $month . "/" . $day . "/" . $year;
$currentPage = basename($_SERVER['PHP_SELF']);
$hasRecord = "";
?>
    <div class="dashboard-wrapper">
      <div class="container-fluid dashboard-content ">
        <div class="card">
          <div class="card-body">
            <!-- non repeated schedules -->
            <div class="card">
              <div class="card-body">
                <table class="table table-bordered table-responsive-md table-striped text-center">
                  <span class="table-add float-left mb-3 mr-2">
                    <a data-toggle="modal" href="#schedModal" class="text-success">
                      <i
                         class="fas fa-plus" aria-hidden="true">
                      </i>
                    </a>  Add Schedule
                  </span>
                  <thead>
                    <col width="150">
                    <col width="150">
                    <col width="150">
                    <col width="150">
                    <col width="150">
                    <tr>
                      <th class="text-center">Date
                      </th>
                      <th class="text-center">Time
                      </th>
                      <th class="text-center">Appliance Name
                      </th>
                      <th class="text-center">Operation
                      </th>
                      <th class="text-center">Actions
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
$query = "SELECT tbl_schedules.scheduleID,DATE_FORMAT(tbl_schedules.scheduleDate, '%M %d %Y') as Date,TIME_FORMAT(tbl_schedules.scheduleTime, '%h:%i %p') as Time,tbl_appliances.applianceName,tbl_schedules.scheduleAction
FROM tbl_schedules
LEFT JOIN tbl_appliances
ON tbl_schedules.scheduleApplianceID=tbl_appliances.applianceID
WHERE tbl_schedules.scheduleRepeat IS NULL
";
$getApplianceSchedule=$conn->prepare($query);
$getApplianceSchedule->execute();
if($getApplianceSchedule->rowCount() == 0){
$hasRecord = "No Records";
}
else
{
$hasRecord = "";
while ($applianceSchedule = $getApplianceSchedule->fetch(PDO::FETCH_ASSOC)) {
if ($applianceSchedule['scheduleAction'] == 0) {
$action = "Turn Off";
} elseif ($applianceSchedule['scheduleAction'] == 1) {
$action = "Turn On";
} elseif ($applianceSchedule['scheduleAction'] == 2) {
$action = "Enable";
} elseif ($applianceSchedule['scheduleAction'] == 3) {
$action = "Disable";
}
echo '<tr><td>'.$applianceSchedule['Date'].'</td>
<td>'.$applianceSchedule['Time'].'</td>
<td>'.$applianceSchedule['applianceName'].'</td>
<td>'.$action.'</td>
<td>
<span class="table-remove"><button type="button"
class="btn btn-danger btn-rounded btn-sm my-0"
onclick="removeSched(\''.$applianceSchedule['scheduleID'].'\')">Remove</button></span></td>';
$repeat = "";
}
}
?>
                  </tbody>
                </table>
                <br>
                <?php echo $hasRecord;?>
              </div>
            </div>
            <!-- non repeated schedules end-->
            <!-- repeated schedules -->
            <div class="card">
              <div class="card-body">
                <table class="table table-bordered table-responsive-md table-striped text-center">
                  <span class="table-add float-left mb-3 mr-2">
                    <a data-toggle="modal" href="#schedRepeatModal" class="text-success">
                      <i
                         class="fas fa-plus" aria-hidden="true">
                      </i>
                    </a>  Add Repeated Schedule
                  </span>
                  <thead>
                    <col width="150">
                    <col width="150">
                    <col width="150">
                    <col width="150">
                    <col width="150">
                    <tr>
                      <th class="text-center">Time
                      </th>
                      <th class="text-center">Appliance Name
                      </th>
                      <th class="text-center">Operation
                      </th>
                      <th class="text-center">Repeat
                      </th>
                      <th class="text-center">Actions
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
$query = "SELECT tbl_schedules.scheduleID,TIME_FORMAT(tbl_schedules.scheduleTime, '%h:%i %p') as Time,tbl_appliances.applianceName,tbl_schedules.scheduleAction,tbl_schedules.scheduleRepeat
FROM tbl_schedules
LEFT JOIN tbl_appliances
ON tbl_schedules.scheduleApplianceID=tbl_appliances.applianceID
WHERE tbl_schedules.scheduleRepeat IS NOT NULL
";
$getApplianceSchedule=$conn->prepare($query);
$getApplianceSchedule->execute();
if($getApplianceSchedule->rowCount() == 0){
$hasRecord = "No Records";
}
else
{
$hasRecord = "";
$count = 0;
$repeat = "";
while ($applianceSchedule = $getApplianceSchedule->fetch(PDO::FETCH_ASSOC)) {
if ($applianceSchedule['scheduleAction'] == 0) {
$action = "Turn Off";
} elseif ($applianceSchedule['scheduleAction'] == 1) {
$action = "Turn On";
} elseif ($applianceSchedule['scheduleAction'] == 2) {
$action = "Enable";
} elseif ($applianceSchedule['scheduleAction'] == 3) {
$action = "Disable";
}
$array = str_split($applianceSchedule['scheduleRepeat']);
foreach ($array as $char) {
$count++;
if ($count == 1 && $char == '1') {
$repeat .= "M ";
} elseif ($count == 2 && $char == '1') {
$repeat .= " T ";
} elseif ($count == 3 && $char == '1') {
$repeat .= " W ";
} elseif ($count == 4 && $char == '1') {
$repeat .= " Th ";
} elseif ($count == 5 && $char == '1') {
$repeat .= " F ";
} elseif ($count == 6 && $char == '1') {
$repeat .= " Sa ";
} elseif ($count == 7 && $char == '1') {
$repeat .= " Sun ";
}
}
echo '<tr>
<td>'.$applianceSchedule['Time'].'</td>
<td>'.$applianceSchedule['applianceName'].'</td>
<td>'.$action.'</td>
<td>'.$repeat.'</td>
<td>
<span class="table-remove"><button type="button"
class="btn btn-danger btn-rounded btn-sm my-0"
onclick="removeSched(\''.$applianceSchedule['scheduleID'].'\')">Remove</button></span></td></tr>';
$repeat = "";
}
}
?>
                  </tbody>
                </table>
                <br>
                <?php echo $hasRecord;?>
              </div>
              <!-- non repeated schedules end-->
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- add schedule modal -->
    <div class="modal fade" id="schedModal" role="dialog">
      <div class="modal-dialog modal-sm">
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">
              <div id = "scheduleTitle"> Add Schedule
              </div>
            </h4>
            <button type="button" class="close" data-dismiss="modal">&times;
            </button>
          </div>
          <div class="modal-body">
            <!-- date picker -->
            Date
            <div class="form-group">
              <div class="input-group date" id="datetimepicker4" data-target-input="nearest">
                <input type="text" onkeydown="return false" value ="12/25/2020" id="scheduleDate" class="form-control datetimepicker-input" data-target="#datetimepicker4" data-toggle="datetimepicker"/>
                <div class="input-group-append" data-target="#datetimepicker4" data-toggle="datetimepicker">
                  <div class="input-group-text">
                    <i class="far fa-calendar-alt">
                    </i>
                  </div>
                </div>
              </div>
            </div>
            <!-- date picker end -->
            <!-- time picker -->
            Time
            <div class="form-group">
              <div class="input-group date" id="datetimepicker3" data-target-input="nearest">
                <input type="text" onkeydown="return false" value ="<?php echo $date;?>" id="scheduleTime" class="form-control datetimepicker-input" data-target="#datetimepicker3" data-toggle="datetimepicker"/>
                <div class="input-group-append" data-target="#datetimepicker3" data-toggle="datetimepicker">
                  <div class="input-group-text">
                    <i class="far fa-clock">
                    </i>
                  </div>
                </div>
              </div>
            </div>
            <!-- time picker end -->
            <!-- select appliance -->
            <div class="form-group">
              <label>Appliance
              </label>
              <select class="form-control" id="applianceSelect">
                <?php
$query = "SELECT `applianceName`,`applianceID` from `tbl_appliances`";
$getApplianceList=$conn->prepare($query);
$getApplianceList->execute();
while ($applianceList = $getApplianceList->fetch(PDO::FETCH_ASSOC)) {
echo '<option value ="'.$applianceList['applianceID'].'">'.$applianceList['applianceName'].'</option>';
}
?>
              </select>
            </div>
            <!-- select appliance end -->
            <!-- select appliance action -->
            <div class="form-group">
              <label for="applianceAction">Action
              </label>
              <select class="form-control" id="scheduleAction">
                <option value ="0">Turn Off
                </option>
                <option value ="1">Turn On
                </option>
                <option value ="2">Enable
                </option>
                <option value ="3">Disable
                </option>
              </select>
              <!-- select appliance action end-->
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" onclick ="addSchedule()" data-dismiss="modal">Save
            </button>
          </div>
        </div>
      </div>
    </div>
    <!-- add schedule modal end -->

     <!-- add repeat schedule modal -->
     <div class="modal fade" id="schedRepeatModal" role="dialog">
      <div class="modal-dialog modal-sm">
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">
              <div id = "scheduleTitle"> Add Schedule
              </div>
            </h4>
            <button type="button" class="close" data-dismiss="modal">&times;
            </button>
          </div>
          <div class="modal-body">
            <!-- time picker -->
            <label>Time
              </label>
            <div class="form-group">
              <div class="input-group date" id="wa" data-target-input="nearest">
                <input type="text" onkeydown="return false" value ="<?php echo $date;?>" id="scheduleRepeatTime" class="form-control datetimepicker-input" data-target="#wa" data-toggle="datetimepicker"/>
                <div class="input-group-append" data-target="#wa" data-toggle="datetimepicker">
                  <div class="input-group-text">
                    <i class="far fa-clock">
                    </i>
                  </div>
                </div>
              </div>
            </div>
            <!-- time picker end -->
            <!-- select appliance -->
            <div class="form-group">
              <label>Appliance
              </label>
              <select class="form-control" id="applianceRepeatSelect">
                <?php
$query = "SELECT `applianceName`,`applianceID` from `tbl_appliances`";
$getApplianceList=$conn->prepare($query);
$getApplianceList->execute();
while ($applianceList = $getApplianceList->fetch(PDO::FETCH_ASSOC)) {
echo '<option value ="'.$applianceList['applianceID'].'">'.$applianceList['applianceName'].'</option>';
}
?>
              </select>
            </div>
            <!-- select appliance end -->
            <!-- select appliance action -->
            <div class="form-group">
              <label for="applianceAction">Action
              </label>
              <select class="form-control" id="scheduleRepeatAction">
                <option value ="0">Turn Off
                </option>
                <option value ="1">Turn On
                </option>
                <option value ="2">Enable
                </option>
                <option value ="3">Disable
                </option>
              </select>
              <!-- select appliance action end-->
            </div>
            <!-- checkboxes for days -->

              <label>Repeat Every
              </label>
              <div class="form-group">
            <div class="form-check-inline">
  <label class="form-check-label">
    <input type="checkbox" class="form-check-input" id="dayM" value="">M
  </label>
</div>
<div class="form-check-inline">
  <label class="form-check-label">
    <input type="checkbox" class="form-check-input" id="dayT" value="">T
  </label>
</div>
<div class="form-check-inline">
  <label class="form-check-label">
    <input type="checkbox" class="form-check-input" id="dayW" value="">W
  </label>
</div>
<div class="form-check-inline">
  <label class="form-check-label">
    <input type="checkbox" class="form-check-input" id="dayTh" value="">Th
  </label>
</div>
<div class="form-check-inline">
  <label class="form-check-label">
    <input type="checkbox" class="form-check-input" id="dayF" value="">F
  </label>
</div>
<div class="form-check-inline">
  <label class="form-check-label">
    <input type="checkbox" class="form-check-input" id="daySa" value="">Sa
  </label>
</div>
<div class="form-check-inline">
  <label class="form-check-label">
    <input type="checkbox" class="form-check-input" id="daySun" value="">Sun
  </label>
</div>
</div>
<!-- checkboxes for days end -->
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" onclick ="addRepeatSchedule()" data-dismiss="modal">Save
            </button>
          </div>
        </div>
      </div>
    </div>
    <!-- add repeat schedule modal end -->
    <script type="text/javascript">
      $(function () {
        $('#datetimepicker4').datetimepicker({
          minDate: new Date(),
          format: 'MM/DD/YYYY'
        }
                                            );
      }
       );
            $(function () {
                $('#wa').datetimepicker({
                    format: 'LT'
                });
            });
    </script>
    <script src="assets/vendor/datepicker/moment.js">
    </script>
    <script src="assets/vendor/datepicker/tempusdominus-bootstrap-4.js">
    </script>
    <script src="assets/vendor/datepicker/datepicker.js">
    </script>
  </body>
</html>
