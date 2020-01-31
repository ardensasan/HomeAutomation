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
                      <th class="text-center">Actions
                      </th>
</tr>
</thead>
</table>
</div>
</div>
</div>
</div>
  </body>
</html>
