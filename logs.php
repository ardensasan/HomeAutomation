<!doctype html>
<?php
include "sessions.php";
include_once "navigator.php";
include_once "databaseConnection.php";
?>
<body>
    <!-- content -->
    <!-- ============================================================== -->
    <div class="dashboard-wrapper">
        <div class="container-fluid dashboard-content ">
            <div class="card">
                <div class="card-body">
                <table id="datable" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Log</th>
                                            <th class="text-center">Date</th>
                                            <th class="text-center">Time</th>
                                            <th class="text-center">Appliance</th>
                                            <th class="text-center">Action</th>
                                            <th class="text-center">Via</th>
                                            <th class="text-center">User</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $count = 1;
                                    $query = "SELECT CONCAT(tbl_users.userFirstName, ' ',tbl_users.userLastName) AS FullName,(tbl_users.userFirstName+' '+tbl_users.userLastName),tbl_logs.logID, DATE_FORMAT(DATE((tbl_logs.logDateTime)),'%M %d %Y') as D, TIME_FORMAT((TIME(tbl_logs.logDateTime)),'%h:%i %p') as T, tbl_logs.logAppliance, tbl_logs.logAction, tbl_logs.logVia FROM tbl_logs
                                    JOIN tbl_users on tbl_logs.logUser=tbl_users.userID
                                    ORDER BY logID DESC";
                                    $getapplianceName=$conn->prepare($query);
                                    $getapplianceName->execute();
                                    if($getapplianceName->rowCount() > 0)
                                    {
                                        $recNotif = "";
                                    while ($applianceName = $getapplianceName->fetch(PDO::FETCH_ASSOC)) {
                                        if ($applianceName['logAction'] == 0) {
                                            $action = "Turn Off";
                                        } elseif ($applianceName['logAction'] == 1) {
                                            $action = "Turn On";
                                        } elseif ($applianceName['logAction'] == 2) {
                                            $action = "Appliance Disabled";
                                        } elseif ($applianceName['logAction'] == 3) {
                                            $action = "Appliance Enabled";
                                        }
                                        if ($applianceName['logVia'] == 0) {
                                            $action2 = "Webpage";
                                            $exec = $applianceName['FullName'];
                                        } elseif ($applianceName['logVia'] == 1) {
                                            $action2 = "Manual";
                                            $exec = "NA";
                                        } elseif ($applianceName['logVia'] == 3) {
                                            $action2 = "Schedule";
                                            $exec = "NA";
                                        }
                                        echo '<tr><td class="text-center">'.$count++.'</td>
                                        <td class="text-center">'. $applianceName['D'].'</td>
                                        <td class="text-center">'. $applianceName['T'].'</td>
                                        <td class="text-center">'.$applianceName['logAppliance'].'</td>
                                        <td class="text-center">'.$action.'</td>
                                        <td class="text-center">'.$action2.'</td>
                                        <td class="text-center">'.$exec.'</td>
                                        </tr>';
                                    }
                                }else{
                                    $recNotif = "No Records Available";
                                    echo '</tbody>'.$recNotif.'</table>';                          
                            }?>
                </div>
            </div>
        </div>
    </div><!-- .animated -->
    <div class="card">
    <button type="button" class="btn btn-info" onclick="clearLogs()">Clear Logs</button>
    </div>
<script>
    $(document).ready(function() {
    $('#datable').DataTable();
} );
    </script>
</body>
 
</html>
