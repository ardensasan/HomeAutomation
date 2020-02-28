<?php
include "../databaseConnection.php";
$userID = $_POST['userID'];
$query = "SELECT tbl_notification_status.notifID,  tbl_notification_status.notifStatus,  tbl_notifications.notifID, tbl_notifications.notifMessage, tbl_notifications.notifText, DATE_FORMAT(tbl_notifications.notifDateTime ,'%W %M %e %Y') as T FROM tbl_notification_status 
JOIN tbl_notifications ON tbl_notification_status.notifID = tbl_notifications.notifID WHERE tbl_notification_status.notifUserID = ? ORDER BY tbl_notifications.notifDateTime DESC ";
$getAllNotif=$conn->prepare($query);
$getAllNotif->execute([$userID]);
while($getNotif = $getAllNotif->fetch(PDO::FETCH_ASSOC))
{
    if($getNotif['notifStatus'] == 0){
        $notifStatus = '<div class="email-list-item email-list-item--unread">';
    }else{
        $notifStatus = '<div class="email-list-item">';
    }
    echo $notifStatus.'<div class="email-list-detail"><span class="date">'.$getNotif['T'].'</span><span class="from" onclick="displayNotifModal('.$getNotif['notifID'].','.$userID.')">'.$getNotif['notifMessage'].'</span>
    <p class="msg" onclick="displayNotifModal('.$getNotif['notifID'].','.$userID.')">'.$getNotif['notifText'].'</p>
</div>
<div class="email-list-actions">
    </label><a class="favorite float-right" href="#" onclick="deleteNotif('.$getNotif['notifID'].','.$userID.',\'displayAllNotif\')"><span><i class="fas fa-trash"></i></span></a>
</div>
</div>';
}
$conn = null;
