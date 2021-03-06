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
$yearList = array();
?>
<div class="dashboard-wrapper">
  <div class="container-fluid dashboard-content">
  <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#billCalculator">Bill Calculator</a>
      <div class="card">
        <div class="card-body">
        <?php 
            $query = "SELECT DISTINCT Year(totalConsStart) AS startYear FROM tbl_totalconsumption ORDER BY year(totalConsStart) DESC";
            $getConsumptionYear=$conn->prepare($query);
            $getConsumptionYear->execute();
            while($consumptionYear = $getConsumptionYear->fetch(PDO::FETCH_ASSOC))
            {
                array_push($yearList,$consumptionYear['startYear']);
            }
           echo 'Year&nbsp;&nbsp;&nbsp;<select id="yearSelect" onchange="changeConsYear()">';
            foreach($yearList as $year) {
              echo '<option value ="'.$year.'">'.$year.'</option>';
            }
            ?>          
          </select>
          <div class="card">
            <h5 class="card-header"><center><span id="yearHeader">Year 2020</span></center></h5>
            <h5>Total Power Consumption for Year <b><span id="consumptionYear">2020</span></b> : <b><span id="totalConsumption">1000</span> KWh</b></h5>
            <div class="card-body">
               <div id="chartContainer"></div>
            </div>
            <h5 class="card-header"><center>Month</center></h5>
          </div>
        </div>
      </div>
  </div>
</div>
<!-- bill calculator modal -->
<div id="billCalculator" class="modal fade bd-example-modal-sm" role="dialog">
  <div class="modal-dialog modal-sm">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
      <h4 class="modal-title">Bill Calculator</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="form-group col-xs-2">
          <div class="input-group input-group-round">
            <div class="input-group-prepend">
              <span class="input-group-text">
                <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;KWh&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fas fa-bolt"></i><span>
              </span>
            </div>
            <input type="number" oninput="calculateBill()" class="form-control filter-list-input" maxlength="20" id="wattTotal" value="0" placeholder="KiloWatt" aria-label="First Name">
          </div>
        </div>
        <div class="form-group col-xs-2">
          <div class="input-group input-group-round">
            <div class="input-group-prepend">
              <span class="input-group-text">
                <span>Php/KWh&nbsp;&nbsp;&nbsp;<i class="fas fa-money-bill-alt"></i><span>
              </span>
            </div>
            <input type="number" oninput="calculateBill()" class="form-control filter-list-input" maxlength="20" id="wattPrice" value="0" placeholder="Price Per KiloWatt" aria-label="First Name">
          </div>
        </div>
        <div class="form-group col-xs-2">
          <div class="input-group input-group-round">
            <span class="input-group-text">
                <span>Total</i><span>
              </span>
            <input type="text" class="form-control filter-list-input" disabled maxlength="20" id="totalPrice" value="0 Php" placeholder="Price Per KiloWatt" aria-label="First Name">
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- end bill calculator modal -->
<script>
$( document ).ready(function() {
  changeConsYear();
});
</script>
<script src="js/canvasjs.min.js"></script>
</body>
</html>
