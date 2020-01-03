<?php
include "../sessions.php";
include "../databaseConnection.php";
$userName = $_POST['userName'];
$passWord = $_POST['passWord'];
$state = "3";
//check if account exists
$query = "SELECT * FROM `tbl_users` WHERE `userName` = ? AND `userPass` = ?";
$getUserDetails=$conn->prepare($query);
$getUserDetails->execute([$userName,$passWord]);
if($getUserDetails->rowCount() > 0) {
    while($userDetails = $getUserDetails->fetch(PDO::FETCH_ASSOC)){
        $_SESSION['userType'] = $userDetails['userType'];
        $_SESSION['userID'] = $userDetails['userID'];
        $_SESSION['userFullName'] = $userDetails['userFirstName'].' '.$userDetails['userLastName'];
    }
    $state =  "1";
}else{
    $state =  "0";
}
echo $state;
?>