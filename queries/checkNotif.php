<?php
include "../databaseConnection.php";
$userID = $_POST['userID'];
$query = "SELECT tbl_notification_status.notifID,  tbl_notification_status.notifStatus,  tbl_notifications.notifID, tbl_notifications.notifMessage, tbl_notifications.notifText, tbl_notifications.notifDateTime FROM tbl_notification_status 
JOIN tbl_notifications ON tbl_notification_status.notifID = tbl_notifications.notifID WHERE tbl_notification_status.notifUserID = ?";
$getNotifs=$conn->prepare($query);
$getNotifs->execute([$userID]);
echo $getNotifs->rowCount();