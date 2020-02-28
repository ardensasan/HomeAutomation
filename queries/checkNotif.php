<?php
include "../databaseConnection.php";
$notifs = array();
$userID = $_POST['userID'];
$query = "SELECT tbl_notification_status.notifID,  tbl_notification_status.notifStatus,  tbl_notifications.notifID, tbl_notifications.notifMessage, tbl_notifications.notifText, tbl_notifications.notifDateTime FROM tbl_notification_status 
JOIN tbl_notifications ON tbl_notification_status.notifID = tbl_notifications.notifID WHERE tbl_notification_status.notifUserID = ? AND tbl_notification_status.notifStatus = ?";
$getNotifs=$conn->prepare($query);
$getNotifs->execute([$userID,0]);
$unreadNum = $getNotifs->rowCount();

$query = "SELECT tbl_notification_status.notifID,  tbl_notification_status.notifStatus,  tbl_notifications.notifID, tbl_notifications.notifMessage, tbl_notifications.notifText, tbl_notifications.notifDateTime FROM tbl_notification_status 
JOIN tbl_notifications ON tbl_notification_status.notifID = tbl_notifications.notifID WHERE tbl_notification_status.notifUserID = ?";
$getNotifNum=$conn->prepare($query);
$getNotifNum->execute([$userID]);
$messageNum = $getNotifNum->rowCount();

$notifs = array(
    "messageNum" =>$messageNum,
    "unreadNum" => $unreadNum
);
$conn = null;
echo json_encode($notifs);
