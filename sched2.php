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
<div class="container-fluid dashboard content">
<div class="card">
<div class="card-body">
<table class="table table-bordered table-responsive-md table-striped text-center">
<thead>
<col width="100">
<tr>
<th class="text-center">Date
                      </th>
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
$query = "SELECT tbl_schedules.scheduleID,DATE_FORMAT(tbl_schedules.scheduleDate, '%M %d %Y') as Date,TIME_FORMAT(tbl_schedules.scheduleTime, '%h:%i %p') as Time,tbl_appliances.applianceName,tbl_schedules.scheduleAction,tbl_schedules.scheduleRepeat
FROM tbl_schedules
LEFT JOIN tbl_appliances
ON tbl_schedules.scheduleApplianceID=tbl_appliances.applianceID";
$getApplianceSchedule = $conn->prepare($query);
$getApplianceSchedule->execute();
while($applianceSchedule = $getApplianceSchedule->fetch(PDO::FETCH_ASSOC)){
    if ($applianceSchedule['scheduleAction'] == 0) {
        $action = "Turn Off";
        } elseif ($applianceSchedule['scheduleAction'] == 1) {
        $action = "Turn On";
        } elseif ($applianceSchedule['scheduleAction'] == 2) {
        $action = "Enable";
        } elseif ($applianceSchedule['scheduleAction'] == 3) {
        $action = "Disable";
        }
        if(!$applianceSchedule['scheduleRepeat']){
            $repeat = "No";
        }else{
            $repeat = "Yes";
        }
        echo '<tr><td>'.$applianceSchedule['Date'].'</td>
        <td>'.$applianceSchedule['Time'].'</td>
        <td>'.$applianceSchedule['applianceName'].'</td>
        <td>'.$action.'</td>
        <td>'.$repeat.'</td>
        <td>
        <span class="table-remove"><button type="button"
        class="btn btn-danger btn-rounded btn-sm my-0"
        onclick="removeSched(\''.$applianceSchedule['scheduleID'].'\')">Remove</button></span></td>';
}
?>
</tbody>
</table>
</div>
</div>
</div>
</div>
  </body>
</html>
