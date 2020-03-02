<?php
include "../databaseConnection.php";
include "../sessions.php";
$scheduleID = $_POST['scheduleID'];
$schedDetails = array();
//get schedule details
$query = "SELECT scheduleDate,TIME_FORMAT(scheduleTime, '%h:%i %p') as Time,scheduleApplianceID,scheduleAction,scheduleRepeat FROM tbl_schedules WHERE scheduleID = ?";
$getScheduleDetails=$conn->prepare($query);
$getScheduleDetails->execute([$scheduleID]);
while($scheduleDetails = $getScheduleDetails->fetch(PDO::FETCH_ASSOC)){
    if($scheduleDetails['scheduleDate'] != null){
        $date = date("m/d/Y", strtotime($scheduleDetails['scheduleDate']));  
        $date = str_replace('-','/',$date);
    }else{
        $date = "2020-10-10";
    }
    $schedDetails = array(
        "scheduleDate" => $date,
        "scheduleTime" => $scheduleDetails['Time'],
        "scheduleApplianceID" => $scheduleDetails['scheduleApplianceID'],
        "scheduleAction" => $scheduleDetails['scheduleAction'],
        "scheduleRepeat" => $scheduleDetails['scheduleRepeat']
    );
}
$conn = null;
echo json_encode($schedDetails); 
?>
