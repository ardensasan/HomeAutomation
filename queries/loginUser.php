<?php
include "../sessions.php";
include "../databaseConnection.php";
$userName = $_POST['loginUN'];
$passWord = $_POST['loginPW'];
$state = "0";
//check if account exists
$query = "SELECT * FROM `tbl_users` WHERE `userName` = ? AND `userPass` = ?";
$getUserDetails=$conn->prepare($query);
$getUserDetails->execute([$userName,$passWord]);
if($getUserDetails->rowCount() == 0){
    echo "0";
}else{
    while($userDetails = $getUserDetails->fetch(PDO::FETCH_ASSOC)){
        $_SESSION['userType'] = $userDetails['userType'];
        $_SESSION['userID'] = $userDetails['userID'];
        $_SESSION['userFirstName'] = $userDetails['userFirstName'];
        $_SESSION['userLastName'] = $userDetails['userLastName'];
        $_SESSION['userPass'] = $userDetails['userPass'];
        $_SESSION['userPhoneNumber'] = $userDetails['userPhoneNumber'];
        $_SESSION['isLogged'] = 1;
        $conn = null;
        echo "1";
    }
}
?>
