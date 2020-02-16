<?php
include "../databaseConnection.php";
$userID = $_POST['userID'];
$curentDT = date('Y-m-d H:i:s');
$query = "SELECT tbl_notification_status.notifID, tbl_notification_status.notifStatus, tbl_notifications.notifID, tbl_notifications.notifMessage, tbl_notifications.notifText, tbl_notifications.notifDateTime FROM tbl_notification_status 
JOIN tbl_notifications ON tbl_notification_status.notifID = tbl_notifications.notifID WHERE tbl_notification_status.notifUserID = ? ORDER BY tbl_notifications.notifDateTime DESC LIMIT 3";
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
            $messageClass = '<a href="#" onclick="displayNotifModal('.$notifs['notifID'].','.$userID.')" class="list-group-item list-group-item-primary">';
        }else{
            $messageClass = '<a href="#" onclick="displayNotifModal('.$notifs['notifID'].','.$userID.')" class="list-group-item list-group-item-light">';
        }
        $date = strtotime($notifs['notifDateTime']);
        $date =  date('d-M-Y H:i:s', $date);
        $first_date = new DateTime($curentDT);
        $second_date = new DateTime($date);
        $difference = date_diff($first_date,$second_date);
        if($difference->d == 0){
            if($difference->h > 0){
                $difference = '<b>'.$difference->h.'</b>';
                $ago = ' HOUR(S) AGO';
            }else{
                $difference = '<b>'.$difference->i.'</b>';
                $ago = ' MIN(S) AGO';
            }
        }else{
            $difference = '<b>'.$difference->d.'</b>';
            $ago = ' DAY(S) AGO';
        }
        $difference .= $ago;
        echo ''.$messageClass.'
        <div class="notification-info">
            <div> '.$notifs['notifMessage'].'
            <div class="notification-date">'.$difference.'</div>
            </div>
        </div>
        </a>';
    }
}