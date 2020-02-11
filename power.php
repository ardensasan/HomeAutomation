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
$portNumber = 0;
$portName = "";
?>
    <div class="dashboard-wrapper">
      <div class="container-fluid dashboard-content ">
        <?php
$query = "SELECT * FROM `tbl_appliances` WHERE `applianceName` IS NOT NULL AND `applianceStatus` != ?";
$getApplianceList=$conn->prepare($query);
$getApplianceList->execute([2]);
while($applianceList = $getApplianceList->fetch(PDO::FETCH_ASSOC)){
if($portNumber == 0){
$portNumber = $applianceList['applianceID'];
$portName = $applianceList['applianceName'];
echo '<a href="#" class="btn btn-primary" id="portButton'.$portNumber.'" onclick="changeGraph('.$portNumber.')">Port '.$portNumber.'</a>';
}else{
echo '<a href="#" class="btn btn-outline-primary" id="portButton'.$applianceList['applianceID'].'" onclick="changeGraph('.$applianceList['applianceID'].')">Port '.$applianceList['applianceID'].'</a>';
}     
}?>
        <div class="card">
          <input type="hidden" id="portNumber" value="<?php echo $portNumber; ?>">
          <input type="hidden" id="portName" value="<?php echo $portName; ?>">
          <div class="card-body">
            <h5>Port 
              <span id ="portNumberText">
                <?php echo $portNumber." : ".$portName; ?>
              </span>
              <span id ="portNumberReadings"> [ 0 V | 0 A ]
              </span>
            </h5>
            </center>
          <div id="graph1">
          </div>
          <center>
            <h5>Time
            </h5>
          </center>
        </div>
      </div>
    </div>
    </div>
  <script>
    var myVar = setInterval(refreshTable, 100);
    function refreshTable() {
      var portNumber = document.getElementById("portNumber").value;
      var limit = 20;
      $.ajax({
        url: 'queries/getReadings.php',
        type: 'POST',
        data: {
          applianceID: portNumber,limit: limit}
        ,
        dataType: 'JSON',
        success: function(data){
          graph1.setData(data);
          $.ajax({
            url: 'queries/changeGraph.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
              applianceID: portNumber}
            ,
            success: function(result){
              document.getElementById("portNumberReadings").innerHTML = " [ "+result.voltage+" V | "+result.current +"A ]";
            }
          }
                )
        }
      }
            )
    }
  </script>
  <script>
    //line graph 1
    var graph1 = Morris.Line({
      element: 'graph1',
      data: [ 
        {
          DT: "10:01:00", Watt: 10}
        ,
        {
          DT: "10:01:01", Watt: 20}
        ,
        {
          DT: "10:01:02", Watt: 10}
        ,
        {
          DT: "10:01:03", Watt: 20}
        ,
        {
          DT: "10:01:04", Watt: 20}
        ,
        {
          DT: "10:01:05", Watt: 19}
        ,
        {
          DT: "10:01:06", Watt: 21}
        ,
        {
          DT: "10:01:07", Watt: 20}
        ,
        {
          DT: "10:01:08", Watt: 10}
        ,
        {
          DT: "10:01:09", Watt: 15}
        ,
        {
          DT: "10:01:10", Watt: 16}
        ,
        {
          DT: "10:01:11", Watt: 17}
        ,
        {
          DT: "10:01:12", Watt: 19}
        ,
        {
          DT: "10:01:13", Watt: 20}
        ,
        {
          DT: "10:01:14", Watt: 21}
        ,
        {
          DT: "10:01:15", Watt: 22}
        ,
        {
          DT: "10:01:16", Watt: 16}
        ,
        {
          DT: "10:01:17", Watt: 17}
        ,
        {
          DT: "10:01:18", Watt: 15}
        ,
        {
          DT: "10:01:19", Watt: 14}
        ,
        {
          DT: "10:01:20", Watt: 18}
        ,
      ],
      xkey: 'DT',
      ykeys: ['Watt'],
      labels: ['Watts (W)'],
      hideHover: false,
      parseTime: false,
      xLabelAngle: 60
    }
                            );
  </script>
  <script src="js/morris.js">
  </script>
  <link rel="stylesheet" href="css/morris.css">
  </body>
</html>
