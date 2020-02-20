<?php
include_once("databaseConnection.php");
include_once('pdfMaker/fpdf.php');
 
class PDF extends FPDF
{
// Page header
function Header()
{
    // Logo
    $this->SetFont('Arial','B',13);
    // Move to the right
    $this->Cell(80);
    // Title
    $this->Cell(80,10,'Logs',1,0,'C');
    // Line break
    $this->Ln(20);
}
 
// Page footer
function Footer()
{
    // Position at 1.5 cm from bottom
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('Arial','I',8);
    // Page number
    $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
}
}
$pdf = new PDF();
//header
$pdf->AddPage("L");
//foter page
$header = array("No.","Date","Time","Appliance","Action","Via","User");
$pdf->AliasNbPages();
$pdf->SetFont('Arial','B',12);
foreach($header as $head){
    if($head == "No."){
        $pdf->Cell(12,10,$head,1);
    }else{
        $pdf->Cell(44,10,$head,1);
    }
    
}
$count = 1;
$query = "SELECT CONCAT(tbl_users.userFirstName, ' ',tbl_users.userLastName) AS FullName,(tbl_users.userFirstName+' '+tbl_users.userLastName),tbl_logs.logID, DATE_FORMAT(DATE((tbl_logs.logDateTime)),'%M %d %Y') as D, TIME_FORMAT((TIME(tbl_logs.logDateTime)),'%h:%i %p') as T, tbl_logs.logAppliance, tbl_logs.logAction, tbl_logs.logVia FROM tbl_logs
JOIN tbl_users on tbl_logs.logUser=tbl_users.userID
ORDER BY logID DESC";
$getapplianceName=$conn->prepare($query);
$getapplianceName->execute();
if($getapplianceName->rowCount() > 0)
{
    $recNotif = "";
while ($applianceName = $getapplianceName->fetch(PDO::FETCH_ASSOC)) {
    $pdf->Ln();
    if ($applianceName['logAction'] == 0) {
        $action = "Turn Off";
    } elseif ($applianceName['logAction'] == 1) {
        $action = "Turn On";
    } elseif ($applianceName['logAction'] == 2) {
        $action = "Disabled";
    } elseif ($applianceName['logAction'] == 3) {
        $action = "Enabled";
    }
    if ($applianceName['logVia'] == 0) {
        $action2 = "Webpage";
        $exec = $applianceName['FullName'];
    } elseif ($applianceName['logVia'] == 1) {
        $action2 = "Push Button";
        $exec = "NA";
    } elseif ($applianceName['logVia'] == 2) {
        $action2 = "Schedule";
        $exec = "NA";
    } elseif ($applianceName['logVia'] == 3) {
        $action2 = "SMS";
        $exec = $applianceName['FullName'];
    }
    $pdf->Cell(12,10,$count,1);
    $count++;
    $pdf->Cell(44,10,$applianceName['D'],1);
    $pdf->Cell(44,10,$applianceName['T'],1);
    $pdf->Cell(44,10,$applianceName['logAppliance'],1);
    $pdf->Cell(44,10,$action,1);
    $pdf->Cell(44,10,$action2,1);
    $pdf->Cell(44,10,$exec,1);
}
}
$pdf->Output();
?>