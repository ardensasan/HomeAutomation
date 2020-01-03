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
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<div class="dashboard-wrapper">
    <div class="container-fluid dashboard-content ">
        <div class="card">
            <div class="card-body">
            
                <!-- ============================================================== -->
                <!-- end pageheader -->
                <!-- ============================================================== -->
                    <div class="row">
                        <!-- ============================================================== -->
                        <!--bar chart  -->
                        <!-- ============================================================== -->
                        <!-- <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="card">
                                <h5 class="card-header">2019 Power Consumption</h5>
                                <div class="card-body">
                                    <div id="bar-example"></div>
                                </div>
                            </div>
                        </div> -->
                        <!-- line graph #1 start -->
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="card">
                                <h5 class="card-header">Appliance #1</h5>
                                <div class="card-body">
                                    <div id="applianceGraph1"></div>
                                </div>
                            </div>
                        </div>
                        <!-- line graph #1 end -->
                        <!-- line graph #2 start -->
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="card">
                                <h5 class="card-header">Appliance #2</h5>
                                <div class="card-body">
                                    <div id="applianceGraph2"></div>
                                </div>
                            </div>
                        </div>
                        <!-- line graph #2 end -->
                        <!-- line graph #3 start -->
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="card">
                                <h5 class="card-header">Appliance #3</h5>
                                <div class="card-body">
                                    <div id="applianceGraph3"></div>
                                </div>
                            </div>
                        </div>
                        <!-- line graph #3 end -->
                        <!-- line graph #4 start -->
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="card">
                                <h5 class="card-header">Appliance #4</h5>
                                <div class="card-body">
                                    <div id="applianceGraph4"></div>
                                </div>
                            </div>
                        </div>
                        <!-- line graph #4 end -->
                    </div>
                    <div class="row">  
            </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="custId" name="custId" value="3487">
</div>
<script>
function fetchdata(){
 $.ajax({
  url: 'queries/getCurrentReadings.php',
  type: 'post',
  data: {applianceID: 1},
  dataType: 'JSON',
  success: function(data){
   // Perform operation on return value     
   applianceGraph1.setData(data);
   $.ajax({
  url: 'queries/getCurrentReadings.php',
  type: 'post',
  data: {applianceID: 2},
  dataType: 'JSON',
  success: function(data){
   // Perform operation on return value     
   applianceGraph2.setData(data);
      }
    });
    $.ajax({
  url: 'queries/getCurrentReadings.php',
  type: 'post',
  data: {applianceID: 3},
  dataType: 'JSON',
  success: function(data){
   // Perform operation on return value     
   applianceGraph3.setData(data);
      }
    });
    $.ajax({
  url: 'queries/getCurrentReadings.php',
  type: 'post',
  data: {applianceID: 4},
  dataType: 'JSON',
  success: function(data){
   // Perform operation on return value     
   applianceGraph4.setData(data);
      }
    });
},
  complete:function(data){
   setTimeout(fetchdata,2000);
  }
 });
}

$(document).ready(function(){
 setTimeout(fetchdata,2000);
});
</script>

<script>
var applianceGraph1 = Morris.Line({
  element: 'applianceGraph1',
  data: [ 
    { DT: "June", Current: 0, Voltage: 0},
    { DT: "July", Current: 0, Voltage: 0},
    { DT: "August", Current: 0, Voltage: 0},
    { DT: "September", Current: 0, Voltage: 0},
    { DT: "October", Current: 0, Voltage: 0},
    { DT: "November", Current: 0, Voltage: 0},
    { DT: "December", Current: 0, Voltage: 0}],
  xkey: 'DT',
  ykeys: ['Current','Voltage'],
  labels: ['Current (A)','Voltage (V)'],
  hideHover: 'auto',
  parseTime: false
});

var applianceGraph2 = Morris.Line({
  element: 'applianceGraph2',
  data: [ 
    { DT: "June", Current: 0, Voltage: 0},
    { DT: "July", Current: 0, Voltage: 0},
    { DT: "August", Current: 0, Voltage: 0},
    { DT: "September", Current: 0, Voltage: 0},
    { DT: "October", Current: 0, Voltage: 0},
    { DT: "November", Current: 0, Voltage: 0},
    { DT: "December", Current: 0, Voltage: 0}],
  xkey: 'DT',
  ykeys: ['Current','Voltage'],
  labels: ['Current (A)','Voltage (V)'],
  hideHover: 'auto',
  parseTime: false
});

var applianceGraph3 = Morris.Line({
  element: 'applianceGraph3',
  data: [ 
    { DT: "June", Current: 0, Voltage: 0},
    { DT: "July", Current: 0, Voltage: 0},
    { DT: "August", Current: 0, Voltage: 0},
    { DT: "September", Current: 0, Voltage: 0},
    { DT: "October", Current: 0, Voltage: 0},
    { DT: "November", Current: 0, Voltage: 0},
    { DT: "December", Current: 0, Voltage: 0}],
  xkey: 'DT',
  ykeys: ['Current','Voltage'],
  labels: ['Current (A)','Voltage (V)'],
  hideHover: 'auto',
  parseTime: false
});

var applianceGraph4 = Morris.Line({
  element: 'applianceGraph4',
  data: [ 
    { DT: "June", Current: 0, Voltage: 0},
    { DT: "July", Current: 0, Voltage: 0},
    { DT: "August", Current: 0, Voltage: 0},
    { DT: "September", Current: 0, Voltage: 0},
    { DT: "October", Current: 0, Voltage: 0},
    { DT: "November", Current: 0, Voltage: 0},
    { DT: "December", Current: 0, Voltage: 0}],
  xkey: 'DT',
  ykeys: ['Current','Voltage'],
  labels: ['Current (A)','Voltage (V)'],
  hideHover: 'auto',
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
