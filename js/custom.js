var today = new Date();
var dd = String(today.getDate()).padStart(2, '0');
var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
var yyyy = today.getFullYear();

today = mm + '/' + dd + '/' + yyyy;

//change icon class to active or hidden
function changeIconState(page)
{
    // if(page == "dashboard.php"){
    //     document.getElementById("dashboard").className = "active";
    // }else if(page == "appliance.php"){
    //     document.getElementById("appliance").className = "active";
    // }else if(page == "schedule.php"){
    //     document.getElementById("schedule").className = "active";
    // }else if(page == "power.php"){
    //     document.getElementById("power").className = "active";
    // }else if(page == "logs.php"){
    //     document.getElementById("logs").className = "active";
    // }
}
//enable appliance
function enableAppliance(action,applianceID,applianceName,applianceOutputPin,userID)
{
    $.ajax({
        url: "queries/enableAppliance.php",
        method: "POST",
        data: {applianceID: applianceID, action: action, applianceName: applianceName,applianceOutputPin: applianceOutputPin,userID: userID},
        success: function(){
            location.reload();
        }
    })
}
//disable appliance
function disableAppliance(action,applianceID,applianceName,applianceOutputPin,userID)
{
    $.ajax({
        url: "queries/disableAppliance.php",
        method: "POST",
        data: {applianceID: applianceID, action: action, applianceName: applianceName,applianceOutputPin: applianceOutputPin,userID: userID},
        success: function(){
            location.reload();
        }
    })
}
//change appliance status
function changeApplianceStatus(action,applianceID,applianceName,applianceOutputPin,userID)
{
    $.ajax({
        url: "queries/changeApplianceStatus.php",
        method: "POST",
        data: {applianceID: applianceID, action: action, applianceName: applianceName,applianceOutputPin: applianceOutputPin,userID: userID},
        success: function(a){
            location.reload();
        }
    })
}

//change appliance name
function changeApplianceName(applianceID)
{
   var applianceName =  document.getElementById("appliance"+applianceID).innerHTML;
   $.ajax({
       url: "queries/changeApplianceName.php",
       method: "POST",
       data: {applianceID: applianceID, applianceName: applianceName},
       success: function(a){
           location.reload();
        }
    })
}
//check use login credentials
function loginUser()
{
    var loginUN = document.getElementById("loginUN").value;
    var loginPW = document.getElementById("loginPW").value;
    $.ajax({
        url: "queries/loginUser.php",
        method: "POST",
        data: {loginUN: loginUN, loginPW: loginPW},
        success: function(result){
            if(result == "1"){
                document.getElementById("errorMessage").innerHTML = "";
                window.location = "dashboard.php";
            }else{
                document.getElementById("errorMessage").innerHTML = "Account Doesn't Exist";
            }
        }
    })
}

//logout user
function logoutUser()
{
    $.ajax({
        url: "queries/logoutUser.php",
        success: function(){
            window.location ="index.php"
        }
    })
}

//remove schedule
function removeSched(scheduleID)
{
    $.ajax({
        url: "queries/removeSched.php",
        method: "POST",
        data: {scheduleID: scheduleID},
        success: function(){
            location.reload();
        }
    })
}

//remove schedule
function editScheduleDisplay(scheduleID)
{
    $('#schedModal').modal('show');
    $.ajax({
        url: "queries/getSchedDetails.php",
        method: "POST",
        dataType: "JSON",
        data: {scheduleID: scheduleID},
        success: function(result){
            if(result.scheduleRepeat != ""){
                document.getElementById("schedType").value = 2;
                $("#scheduleDateDiv").hide();
                $("#scheduleDateRepeat").show();
                $.ajax({
                    url: "queries/getRepeatDays.php",
                    method: "POST",
                    dataType: "JSON",
                    data: {scheduleID: scheduleID},
                    success: function(result){
                        document.getElementById("dayM").checked =  result.dayM;
                        document.getElementById("dayT").checked =  result.dayT;
                        document.getElementById("dayW").checked =  result.dayW;
                        document.getElementById("dayTh").checked =  result.dayTh;
                        document.getElementById("dayF").checked =  result.dayF;
                        document.getElementById("daySa").checked =  result.daySa;
                        document.getElementById("daySun").checked =  result.daySun;
                    }
                })
            }else{
                $("#scheduleDateDiv").show();
                $("#scheduleDateRepeat").hide();
            }
            if(result.scheduleDate != ""){
                document.getElementById("scheduleDate").value = result.scheduleDate;
            }else{
                document.getElementById("scheduleDate").value = today;
            }
            document.getElementById("scheduleTime").value = result.scheduleTime;
            document.getElementById("applianceSelect").value = result.scheduleApplianceID;
            document.getElementById("scheduleAction").value = result.scheduleAction;
            document.getElementById("scheduleButton").innerHTML = '<button type="button" class="btn btn-primary" onclick="editSchedule('+scheduleID+')">Confirm Edit</button><button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>';
        }
    })
}
//change warning message 
function changeText(elementID,text)
{
    document.getElementById(elementID).innerHTML = text;
}

//change appliance name for modal ready 
function changeApplianceModal(applianceID,applianceName)
{
    document.getElementById("applianceName").placeholder = applianceName;
    document.getElementById("applianceName").placeholder = applianceName;
    document.getElementById("applianceID").value = applianceID;
}

//save edit profile settings
function saveSettings()
{
    var settingsFirstName = document.getElementById("settingsFirstName").value;
    var settingsLastName = document.getElementById("settingsLastName").value;
    var settingsPass1 = document.getElementById("settingsPass1").value;
    var settingsPass2 = document.getElementById("settingsPass2").value;
    var settingsPhoneNumber = document.getElementById("settingsPhoneNumber").value;
    if(settingsFirstName && settingsLastName && settingsPass1 && settingsPass2 && settingsPhoneNumber){
        if(settingsPass1 == settingsPass2){ 
            $.ajax({
                url: "queries/saveSettings.php",
                method: "POST",
                data: {userFirstName: settingsFirstName, userLastName : settingsLastName, userPass : settingsPass2, userPhoneNumber : settingsPhoneNumber },
                success: function(){
                    alert("Profile Successfully Updated");
                    location.reload();
                }
            })
        }else{
            alert("password not the same");
        }
    }else{
        alert("Fill all fields");
    }
}


//change appliance name
function changeApplianceName(applianceID,previousApplianceName)
{
    var applianceName = document.getElementById("applianceName").value;
    $.ajax({
        url: "queries/changeApplianceName.php",
        method: "POST",
        data: {applianceID: applianceID,applianceName: applianceName},
        success: function(a){
            document.getElementById("applianceName").value = "";
            if(a == "1"){
                alert("An appliance with the same name is already registered!");
            }else{
                var deleteRecords = confirm("Do you wish to delete all schedules associated with "+ previousApplianceName + "\n Ok : Delete Schedules \n Cancel: Apply schedules to "+applianceName );
                if(deleteRecords){
                    $.ajax({
                        url: "queries/deleteSchedules.php",
                        method: "POST",
                        data: {applianceID: applianceID},
                        success: function(){
                        }
                    })
                }
            }
            location.reload();
        }
    })
}
//display schedule forms inputs
function displaySchedForm(){
    var schedType = document.getElementById("schedType").value;
    if(schedType == 1){
        $("#scheduleDateDiv").show();
        $("#scheduleDateRepeat").hide();
    }else{
        $("#scheduleDateDiv").hide();
        $("#scheduleDateRepeat").show();
    }
}
//add schedule display
function addScheduleDisplay(){
    $('#schedModal').modal('show');
    document.getElementById("scheduleDate").value = today;
    document.getElementById("scheduleButton").innerHTML = '<button type="button" class="btn btn-primary" onclick="addSchedule()">Add</button><button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>';
}
//add schedule
function addSchedule()
{
    var scheduleRepeat = "";
    var schedType = document.getElementById("schedType").value;
    var scheduleDate = document.getElementById("scheduleDate").value;
    var scheduleTime = document.getElementById("scheduleTime").value;
    var scheduleApplianceID = document.getElementById("applianceSelect").value;
    var scheduleAction =  document.getElementById("scheduleAction").value;
    if(schedType == 1){
        scheduleRepeat = null;
    }else{
        scheduleDate = null;
        if(document.getElementById("dayM").checked){
            scheduleRepeat +=  "1";
        }else{
            scheduleRepeat +=  "0";
        }
        if(document.getElementById("dayT").checked){
            scheduleRepeat +=  "1";
        }else{
            scheduleRepeat +=  "0";
        }
        if(document.getElementById("dayW").checked){
            scheduleRepeat +=  "1";
        }else{
            scheduleRepeat +=  "0";
        }
        if(document.getElementById("dayTh").checked){
            scheduleRepeat +=  "1";
        }else{
            scheduleRepeat +=  "0";
        }
        if(document.getElementById("dayF").checked){
            scheduleRepeat +=  "1";
        }else{
            scheduleRepeat +=  "0";
        }
        if(document.getElementById("daySa").checked){
            scheduleRepeat +=  "1";
        }else{
            scheduleRepeat +=  "0";
        }
        if(document.getElementById("daySun").checked){
            scheduleRepeat +=  "1";
        }else{
            scheduleRepeat +=  "0";
        }
    }
    if(scheduleRepeat == "0000000"){
        alert("Please choose a day to repeat");
    }else{
        $.ajax({
            url: "queries/addSchedule.php",
            method: "POST",
            data: {scheduleDate: scheduleDate,scheduleTime: scheduleTime,scheduleApplianceID:scheduleApplianceID,scheduleAction:scheduleAction,scheduleRepeat:scheduleRepeat},
            success: function(){
                location.reload();
            }
        })
    }
}

function editSchedule(scheduleID){
    var scheduleRepeat = "";
    var schedType = document.getElementById("schedType").value;
    var scheduleDate = document.getElementById("scheduleDate").value;
    var scheduleTime = document.getElementById("scheduleTime").value;
    var scheduleApplianceID = document.getElementById("applianceSelect").value;
    var scheduleAction =  document.getElementById("scheduleAction").value;
    if(schedType == 1){
        scheduleRepeat = null;
    }else{
        scheduleDate = null;
        if(document.getElementById("dayM").checked){
            scheduleRepeat +=  "1";
        }else{
            scheduleRepeat +=  "0";
        }
        if(document.getElementById("dayT").checked){
            scheduleRepeat +=  "1";
        }else{
            scheduleRepeat +=  "0";
        }
        if(document.getElementById("dayW").checked){
            scheduleRepeat +=  "1";
        }else{
            scheduleRepeat +=  "0";
        }
        if(document.getElementById("dayTh").checked){
            scheduleRepeat +=  "1";
        }else{
            scheduleRepeat +=  "0";
        }
        if(document.getElementById("dayF").checked){
            scheduleRepeat +=  "1";
        }else{
            scheduleRepeat +=  "0";
        }
        if(document.getElementById("daySa").checked){
            scheduleRepeat +=  "1";
        }else{
            scheduleRepeat +=  "0";
        }
        if(document.getElementById("daySun").checked){
            scheduleRepeat +=  "1";
        }else{
            scheduleRepeat +=  "0";
        }
    }
    if(scheduleRepeat == "0000000"){
        alert("Please choose a day to repeat");
    }else{
        $.ajax({
            url: "queries/editSchedule.php",
            method: "POST",
            data: {scheduleID: scheduleID, scheduleDate: scheduleDate,scheduleTime: scheduleTime,scheduleApplianceID:scheduleApplianceID,scheduleAction:scheduleAction,scheduleRepeat:scheduleRepeat},
            success: function(){
                location.reload();
            }
        })
    }
}
//check use login credentials
function getReadings()
{
    var loginUN = document.getElementById("username").value;
    var loginPW = document.getElementById("password").value;
    $.ajax({
        url: "queries/getCurrentReadings.php",
        method: "POST",
        success: function(accountStatus){
            alert(accountStatus)
        }
    })
}

//remove account
function removeAccount(userID)
{
    $.ajax({
        url: "queries/removeAccount.php",
        method: "POST",
        data: {userID: userID},
        success: function(a){
            location.reload();
        }
    })
}

//clear all logs
function clearLogs()
{
    $.ajax({
        url: "queries/clearLogs.php",
        success: function(accountStatus){
            location.reload();
        }
    })
}

//display calibration modal
function calibrateDisplay(applianceID,applianceName)
{
    document.getElementById("calibrateCountdown").innerHTML = '<button type="button" class="btn btn-primary" onclick="calibrateCount()"><span id="calText">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Teach&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></button>';
    document.getElementById("calAppID").value = applianceID;
    document.getElementById("calAppName").value = applianceName;
    document.getElementById("calAppliance").innerHTML = applianceName;
    document.getElementById("calMessage").innerHTML = "Turn On Appliance Before Teaching";
    $('#calibrateModal').modal('show');
}

//edit appliance name modal
function editApplianceDisplay(applianceID,applianceName)
{
    document.getElementById("editPortNum").innerHTML = "";
        $.ajax({
        url: "queries/changePort.php",
        method: "POST",
        data: {applianceID:applianceID},
        success: function(a){
            $('#editPortNum').append(a); 
            $("#editPortNum").val(applianceID);
        }
    })
    document.getElementById("editAppID").value = applianceID;
    document.getElementById("editedApplianceName").value = applianceName;
    $('#editApplianceModal').modal('show');
}

//edit appliance name
function editApplianceName()
{
    var applianceID = document.getElementById("editAppID").value;
    var applianceName = document.getElementById("editedApplianceName").value;
    var appliancePort = document.getElementById("editPortNum").value;
    if(applianceName == ""){
        alert("Please enter an appliance name");
    }else{
        $.ajax({
            url: "queries/editApplianceName.php",
            method: "POST",
            data: {applianceID:applianceID,applianceName:applianceName,appliancePort: appliancePort},
            success: function(){
                location.reload();
            }
        })
    }
}

//edit appliance name
function addAppliance()
{
    var applianceName = document.getElementById("addApplianceName").value;
    var applianceID = document.getElementById("portNum").value;
    if(applianceName == ""){
        alert("Please enter an appliance name");
    }else{
        $.ajax({
            url: "queries/addAppliance.php",
            method: "POST",
            data: {applianceID:applianceID,applianceName:applianceName},
            success: function(result){
                if(result == "0"){
                    alert("Appliance with the same name already present");
                }else{
                    location.reload();
                }
            }
        })
    }
}

//remove appliance
function removeAppliance(applianceID,applianceOutputPin)
{
    $.ajax({
        url: "queries/removeAppliance.php",
        method: "POST",
        data: {applianceID:applianceID,applianceOutputPin:applianceOutputPin},
        success: function(){
            location.reload();
        }
    })
}

//display calibration modal
function calibrateCount()
{
    var applianceID = document.getElementById("calAppID").value;
    var count = 50;
    document.getElementById("calibrateCountdown").innerHTML = '<button class="btn btn-primary" disabled><span id="calText">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Please Wait '+count+' seconds&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></button>';
    var countDown = setInterval(
        function(){ 
            count--;
            document.getElementById("calibrateCountdown").innerHTML = '<button class="btn btn-primary" disabled><span id="calText">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Please Wait '+count+' seconds&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></button>';
            $.ajax({
                url: "queries/checkApplianceStatus.php",
                method: "POST",
                data: {applianceID:applianceID},
                success: function(result){
                    if(result == 0){
                        document.getElementById("calMessage").innerHTML = "&nbsp;&nbsp;&nbsp;&nbsp;Port "+applianceID+" was turned off. Please try again"
                        clearInterval(countDown);
                    }
                }
            })
            if(count == 0){
                $.ajax({
                    url: "queries/setRating.php",
                    method: "POST",
                    data: {applianceID:applianceID},
                    dataType: 'JSON',
                    success: function(result){
                        var LCLStatus = "";
                        if(result.LCL < 0){
                            LCLStatus = " LCL lower than zero will be ignored"
                        }
                        if(result.avg == null){
                            document.getElementById("calMessage").innerHTML = "There was an error encountered. Please try again";
                        }else{
                            document.getElementById("calMessage").innerHTML = '&nbsp;&nbsp;Average Power: '+result.avg+' W<br>&nbsp;&nbsp;Upper Control Limit: '+result.UCL+' W<br>&nbsp;&nbsp;Lower Control Limit: '+result.LCL+' W<br>'+LCLStatus;
                        }
                    }
                })
                document.getElementById("calibrateCountdown").innerHTML = '<button type="button" class="btn btn-primary" data-dismiss ="modal" onclick="window.location.reload()"><span id="calText">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Finished&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></button>';
                clearInterval(countDown);
            }
        }, 
    1000);   
}


//refresh appliance page
function refreshAppliancePage(){
    $.ajax({
        url: "queries/applianceDisplay.php",
        success: function(display){
            $("#applianceDisplay").html(display);
        }
    });
}

//change graph according to port display
function changeGraph(applianceID){
    var i;
    for (i = 1; i < 5; i++) {
        if(document.getElementById("portButton"+i)){
            if(applianceID == i){
                document.getElementById("portButton"+i).className = 'btn btn-primary';
            }else{
                document.getElementById("portButton"+i).className = 'btn btn-outline-primary';
            }
        }
      }
      $.ajax({
        url: "queries/changeGraph.php",
        method: "POST",
        dataType: 'JSON',
        data: {applianceID:applianceID},
        success: function(result){
            document.getElementById("portNumberText").innerHTML = applianceID+" : "+result.applianceName;
            document.getElementById("portName").value = result.applianceName;
            document.getElementById("portNumber").value = applianceID;
            document.getElementById("portNumberReadings").innerHTML = " [ "+result.voltage+" V | "+result.current +"A ]";
        }
    })
}
//check for notifications
function checkNotif(userID){ 
    $.ajax({
        url: "queries/checkNotif.php",
        method: "POST",
        data: {userID:userID},
        dataType: 'JSON',
        success: function(result){
            if(result.unreadNum == "0"){
                document.getElementById("notifIndicator").classList.remove("indicator");
            }else{
                document.getElementById("notifIndicator").classList.add("indicator");
            }
            if(result.messageNum > 3){
                document.getElementById("viewNotif").innerHTML = '<div class="list-footer" id="viewNotif"> <a href="#" onclick="displayAllNotif('+userID+')">View all notifications</a></div>';
            }else{
                document.getElementById("viewNotif").innerHTML = "";
            }
        }
    })
}

//display notifications
function displayNotif(userID){
    document.getElementById("notifList").innerHTML = "";
    $.ajax({
        url: "queries/displayNotif.php",
        method: "POST",
        data: {userID:userID},
        success: function(result){
            $('#notifList').append(result);
        }
    })
}

//display notification modal
function displayNotifModal(notifID,userID){
    $('#displayAllNotif').modal('hide');
    $.ajax({
        url: "queries/displayNotifDetails.php",
        method: "POST",
        data: {notifID:notifID,userID:userID},
        dataType: 'JSON',
        success: function(result){
            document.getElementById("notifText").innerHTML = result.notifText;
            document.getElementById("notifMessage").innerHTML = result.notifMessage;
            document.getElementById("notifFooter").innerHTML = '<button type="button" onclick="deleteNotif('+notifID+','+userID+',\'displayNotifDetails\')" class="btn btn-danger">Delete</button><button type="button" class="btn btn-default" data-dismiss="modal">Close</button>';
        }
    })
    $('#notifDetails').modal('show');
}

//delete notification
function deleteNotif(notifID,userID,sender){
    $.ajax({
        url: "queries/deleteNotif.php",
        method: "POST",
        data: {notifID:notifID,userID:userID},
        success: function(result){
            $('#notifDetails').modal('hide');
            if(sender == 'displayAllNotif'){
                displayAllNotif(userID);
            }
           }
    })
}

//display all notifications
function displayAllNotif(userID){
    document.getElementById("allNotif").innerHTML = "";
    $.ajax({
        url: "queries/displayAllNotif.php",
        method: "POST",
        data: {userID:userID},
        success: function(result){
            $('#allNotif').append(result); 
        }
    })
    $('#displayAllNotif').modal('show');
}

//change consumption year
function changeConsYear(){
    var year = document.getElementById("yearSelect").value;
    document.getElementById("consumptionYear").innerHTML = year;
    document.getElementById("yearHeader").innerHTML = "Year "+year;
    $.ajax({
        url: "queries/totalConsumption.php",
        method: "POST",
        data: {year:year},
        success: function(result){
            document.getElementById("totalConsumption").innerHTML = result;
            monthlyConsumption();
        }
    })
}
//monthly consumption
function monthlyConsumption(){
    var year = document.getElementById("yearSelect").value;
    $.ajax({
        url: "queries/monthlyConsumption.php",
        method: "POST",
        data: {year:year},
        dataType: 'JSON',
        success: function(result){
            var chart = new CanvasJS.Chart("chartContainer", {
            animationEnabled: true,
            theme: "light1", // "light1", "light2", "dark1", "dark2"
            axisY: {
              title: "KiloWatt Hours (KWh)"
            },
            axisX:{
              interval: 1,
            },
            data: [{        
              type: "column",  
              showInLegend: true, 
              legendMarkerColor: "white",
              legendText: "Month",
              indexLabel: "{y}",
              dataPoints: result
            }]
          });
          chart.render();
        }
    })
}

//calculate bill
function calculateBill(){
    var kiloWatt = document.getElementById("wattTotal").value;
    var price = document.getElementById("wattPrice").value;
    var bill = (kiloWatt*price).toFixed(2);
    document.g
    etElementById("totalPrice").value = bill+" Php";
}
//edit appliance name
function addAccount()
{
    var firstName = document.getElementById("addAccountFirstName").value;
    var lastName = document.getElementById("addAccountLastName").value;
    var userName = document.getElementById("addAccountUserName").value;
    var PW1 = document.getElementById("addAccountPassword").value;
    var PW2 = document.getElementById("addAccountCPassword").value;
    if(!firstName || !lastName || !userName || !PW1 || !PW2){
        alert("Fill in all fields")
    }else if(PW1 != PW2){
        alert("Passwords not the same")
    }else{
        $.ajax({
            url: "queries/addAccount.php",
            method: "POST",
            data: {firstName:firstName,lastName:lastName,userName:userName,PW1:PW1},
            success: function(){
                location.reload();
            }
        })
    }
}

//test
function test(){
    alert("This is a test")
}