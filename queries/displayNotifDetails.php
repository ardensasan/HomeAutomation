<?php
include "../databaseConnection.php";
$notifID = $_POST['notifID'];
$userID = $_POST['userID'];
$notifDetails = array();
$query = "SELECT *FROM `tbl_notifications` WHERE `notifID` = ?";
$getNotifs=$conn->prepare($query);
$getNotifs->execute([$notifID]);
while($getNotifDetails = $getNotifs->fetch(PDO::FETCH_ASSOC))
{
    $notifDetails = array(
        "notifText" => $getNotifDetails['notifText'],
        "notifMessage" => $getNotifDetails['notifMessage']
    );
}
$query = "UPDATE `tbl_notification_status` SET `notifStatus`= ? WHERE `notifUserID` = ? AND `notifID` = ?";
$updateNotifStatus=$conn->prepare($query);
$updateNotifStatus->execute([1,$userID,$notifID]);
$conn = null;
echo json_encode($notifDetails);
