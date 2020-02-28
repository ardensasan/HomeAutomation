<?php
include "../databaseConnection.php";
$userID = $_POST['userID'];
$notifID = $_POST['notifID'];
$query = "DELETE FROM tbl_notification_status WHERE notifID = ? AND notifUserID = ?";
$deleteNotif=$conn->prepare($query);
$deleteNotif->execute([$notifID,$userID]);
$conn = null;
