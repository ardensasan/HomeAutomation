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
include_once "navigator.php";
?>
<div class="dashboard-wrapper">
    <div class="container-fluid dashboard-content ">
        <div class="card">
            <div class="card-body">
                <!-- ============================================================== -->
                <!-- end pageheader -->
                <!-- ============================================================== -->
                    <div class="row">
                    <?php
                    $currentPage = basename($_SERVER['PHP_SELF']);
                    $count = 1;
                    $query = "SELECT * FROM `tbl_appliances`";
                    $getApplianceList=$conn->prepare($query);
                    $getApplianceList->execute();
                    while($applianceList = $getApplianceList->fetch(PDO::FETCH_ASSOC))
                    {?>
                      <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                      <div class="card">
                          <h5 class="card-header"><?php echo $applianceList['applianceName'] ?><span id="span<?php echo $count; ?>"></span></h5>
                          <div class="card-body">
                              <div id="applianceGraph<?php echo $count; ?>"></div>
                          </div>
                      </div>
                  </div><?php
                  $count++;
                    }?>
                    <div class="row">  
            </div>
            </div>
        </div>
    </div>
</div>
<script>
function fetchdata(){
 $.ajax({
  url: 'queries/getReadings.php',
  type: 'post',
  data: {applianceID: 1},
  dataType: 'JSON',
  success: function(data){
   // Perform operation on return value     
   applianceGraph1.setData(data);
},
  complete:function(data){
   setTimeout(fetchdata,1000);
  }
 });
 //appliance no 2
 $.ajax({
  url: 'queries/getReadings.php',
  type: 'post',
  data: {applianceID: 2},
  dataType: 'JSON',
  success: function(data){
   // Perform operation on return value     
   applianceGraph2.setData(data);
},
  complete:function(data){
   setTimeout(fetchdata,1000);
  }
 });
  //appliance no 3
  $.ajax({
  url: 'queries/getReadings.php',
  type: 'post',
  data: {applianceID: 3},
  dataType: 'JSON',
  success: function(data){
   // Perform operation on return value     
   applianceGraph3.setData(data);
},
  complete:function(data){
   setTimeout(fetchdata,1000);
  }
 });
  //appliance no 4
  $.ajax({
  url: 'queries/getReadings.php',
  type: 'post',
  data: {applianceID: 4},
  dataType: 'JSON',
  success: function(data){
   // Perform operation on return value     
   applianceGraph4.setData(data);
},
  complete:function(data){
   setTimeout(fetchdata,1000);
  }
 });
}

$(document).ready(function(){
 setTimeout(fetchdata,1000);
});
</script>

<script>
var applianceGraph1 = Morris.Line({
  element: 'applianceGraph1',
  data: [ 
    { DT: "June", Watt: 0},
    { DT: "July", Watt: 0},
    { DT: "August", Watt: 0},
    { DT: "September", Watt: 0},
    { DT: "October", Watt: 0},
    { DT: "November", Watt: 0},
    { DT: "December", Watt: 0}],
  xkey: 'DT',
  ykeys: ['Watt'],
  labels: ['Watt (W)'],
  hideHover: false,
  parseTime: false
});

var applianceGraph2 = Morris.Line({
  element: 'applianceGraph2',
  data: [ 
    { DT: "June", Watt: 0},
    { DT: "July", Watt: 0},
    { DT: "August", Watt: 0},
    { DT: "September", Watt: 0},
    { DT: "October", Watt: 0},
    { DT: "November", Watt: 0},
    { DT: "December", Watt: 0}],
  xkey: 'DT',
  ykeys: ['Watt'],
  labels: ['Watt (W)'],
  hideHover: false,
  parseTime: false
});

var applianceGraph3 = Morris.Line({
  element: 'applianceGraph3',
  data: [ 
    { DT: "June", Watt: 0},
    { DT: "July", Watt: 0},
    { DT: "August", Watt: 0},
    { DT: "September", Watt: 0},
    { DT: "October", Watt: 0},
    { DT: "November", Watt: 0},
    { DT: "December", Watt: 0}],
  xkey: 'DT',
  ykeys: ['Watt'],
  labels: ['Watt (W)'],
  hideHover: false,
  parseTime: false
});

var applianceGraph4 = Morris.Line({
  element: 'applianceGraph4',
  data: [ 
    { DT: "June", Watt: 0},
    { DT: "July", Watt: 0},
    { DT: "August", Watt: 0},
    { DT: "September", Watt: 0},
    { DT: "October", Watt: 0},
    { DT: "November", Watt: 0},
    { DT: "December", Watt: 0}],
  xkey: 'DT',
  ykeys: ['Watt'],
  labels: ['Watt (W)'],
  hideHover: false,
  parseTime: false
});

</script>
<script>
Morris.Bar({
  element: 'bar-example',
  data: [
    { y: "June", a: 100},
    { y: "July", a: 75},
    { y: "August", a: 50},
    { y: "September", a: 75},
    { y: "October", a: 50},
    { y: "November", a: 75},
    { y: "December", a: 100}
  ],
  xkey: 'y',
  ykeys: ['a'],
  labels: ['Power Consumption', 'Series B']
});
 </script>
    <script src="js/morris.js"></script>
    <link rel="stylesheet" href="css/morris.css">
</body>
</html>
