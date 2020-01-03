<?php
include "../databaseConnection.php";
include "../sessions.php";
$userID = $_SESSION['userID'];
$userFirstName = $_POST['userFirstName'];
$userLastName = $_POST['userLastName'];
$userPass = $_POST['userPass'];
$userPhoneNumber = $_POST['userPhoneNumber'];
$_SESSION['userFirstName'] = $userFirstName;
$_SESSION['userLastName'] = $userLastName;
$_SESSION['userPass'] = $userPass;
$_SESSION['userPhoneNumber'] = $userPhoneNumber;
$query = "UPDATE `tbl_users` SET `userFirstName` = ?, `userLastName` = ?, `userPass` = ?, `userPhoneNumber` = ? WHERE `userID` = ?";
$updateProfile = $conn->prepare($query);
$updateProfile->execute([$userFirstName,$userLastName,$userPass,$userPhoneNumber,$userID]);
?>