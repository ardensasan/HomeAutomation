<?php
include "../databaseConnection.php";
include "../sessions.php";
$firstName = $_POST['firstName'];
$lastName = $_POST['lastName'];
$userName = $_POST['userName'];
$PW1 = $_POST['PW1'];
//enable actions on appliance
$query = "INSERT INTO `tbl_users` (`userID`, `userName`, `userPass`, `userProf`, `userType`, `userFirstName`, `userLastName`, `userPhoneNumber`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
$addAccount=$conn->prepare($query);
$addAccount->execute([NULL,$userName,$PW1,"",1,$firstName,$lastName,""]);
$conn = null;
?>