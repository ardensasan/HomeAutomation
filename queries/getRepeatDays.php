<?php 
include "../sessions.php";
include "../databaseConnection.php";
$scheduleID = $_POST['scheduleID'];
$query = "SELECT scheduleRepeat FROM tbl_schedules WHERE scheduleID = ?";
$getScheduleDetails=$conn->prepare($query);
$getScheduleDetails->execute([$scheduleID]);
while($scheduleDetails = $getScheduleDetails->fetch(PDO::FETCH_ASSOC))
{
    $scheduleRepeat = $scheduleDetails['scheduleRepeat'];
}

$conn = null;
$schedDays = array(
    "dayM" =>  boolval($scheduleRepeat[0]),
    "dayT" => boolval($scheduleRepeat[1]),
    "dayW" => boolval($scheduleRepeat[2]),
    "dayTh" => boolval($scheduleRepeat[3]),
    "dayF" => boolval($scheduleRepeat[4]),
    "daySa" => boolval($scheduleRepeat[5]),
    "daySun" => boolval($scheduleRepeat[6])
);
echo json_encode($schedDays)
?>
