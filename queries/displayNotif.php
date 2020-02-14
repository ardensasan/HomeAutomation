<?php
include "../databaseConnection.php";
$userID = $_POST['userID'];
$query = "SELECT tbl_notification_status.notifID,  tbl_notification_status.notifStatus,  tbl_notifications.notifID, tbl_notifications.notifMessage, tbl_notifications.notifText, tbl_notifications.notifDateTime FROM tbl_notification_status 
JOIN tbl_notifications ON tbl_notification_status.notifID = tbl_notifications.notifID WHERE tbl_notification_status.notifUserID = 0 ORDER BY tbl_notifications.notifDateTime DESC LIMIT 3";
$getNotifs=$conn->prepare($query);
$getNotifs->execute([$userID]);
if($getNotifs->rowCount() == 0) {
    echo '<a class="list-group-item list-group-item-light">
    <div class="notification-info">
        <div><center>No Notifications</center>
        </div>
    </div>
</a>';
}else{
    while($notifs = $getNotifs->fetch(PDO::FETCH_ASSOC)){
        if($notifs['notifStatus'] == 0){
            $messageClass = '<a href="#" onclick="displayNotifModal('.$notifs['notifStatus'].')" class="list-group-item list-group-item-primary">';
        }else{
            $messageClass = '<a href="#" onclick="displayNotifModal()" class="list-group-item list-group-item-light">';
        }
        echo ''.$messageClass.'
        <div class="notification-info">
            <div> '.$notifs['notifMessage'].'
            <div class="notification-date">2 min ago</div>
            </div>
        </div>
        </a>';
    }
}