<?php
include "../databaseConnection.php";
$userID = $_POST['userID'];
$query = "SELECT *FROM `tbl_notifications` WHERE `notifUserID` = ? AND `notifStatus` = ? ORDER BY `notifDateTime` DESC LIMIT 3";
$getNotifs=$conn->prepare($query);
$getNotifs->execute([$userID,0]);
if($getNotifs->rowCount() == 0) {
    echo '<a href="#" class="list-group-item list-group-item-primary">
    <div class="notification-info">
        <div> No Notifications
        </div>
    </div>
</a>';
}else{
    while($notifs = $getNotifs->fetch(PDO::FETCH_ASSOC)){
        if($notifs['notifStatus'] == 0){
            $messageClass = '<a href="#" onclick="displayNotifModal()" class="list-group-item list-group-item-primary">';
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