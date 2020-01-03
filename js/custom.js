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
    var loginUN = document.getElementById("username").value;
    var loginPW = document.getElementById("password").value;
    $.ajax({
        url: "queries/loginUser.php",
        method: "POST",
        data: {userName: loginUN, passWord: loginPW},
        success: function(accountStatus){
            if(accountStatus == "0"){
                changeText("errorMessage","Account doesn't exist");
            }else if(accountStatus == "1"){
                changeText("errorMessage"," ")
                alert("Successfully logged in!");
                window.location = "dashboard.php";
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

//add schedule
function addSchedule()
{
    var scheduleDate = document.getElementById("scheduleDate").value;
    var scheduleTime = document.getElementById("scheduleTime").value;
    var sel = document.getElementById("applianceSelect");
    var scheduleApplianceID = sel.options[sel.selectedIndex].value;
    var sel2 = document.getElementById("scheduleAction");
    var scheduleAction = sel2.options[sel2.selectedIndex].value;
    $.ajax({
        url: "queries/addSchedule.php",
        method: "POST",
        data: {scheduleDate: scheduleDate,scheduleTime: scheduleTime,scheduleApplianceID:scheduleApplianceID,scheduleAction:scheduleAction},
        success: function(){
            location.reload();
        }
    })
}
//add repeated schedule
function addRepeatSchedule()
{
    var scheduleTime = document.getElementById("scheduleRepeatTime").value;
    var sel = document.getElementById("applianceRepeatSelect");
    var scheduleApplianceID = sel.options[sel.selectedIndex].value;
    var sel2 = document.getElementById("scheduleRepeatAction");
    var scheduleAction = sel2.options[sel2.selectedIndex].value;
    var scheduleRepeat = "";
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
    $.ajax({
        url: "queries/addRepeatSchedule.php",
        method: "POST",
        data: {scheduleTime: scheduleTime,scheduleApplianceID:scheduleApplianceID,scheduleAction:scheduleAction,scheduleRepeat:scheduleRepeat},
        success: function(){
            location.reload();
        }
    })
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
function removeAccount()
{
    alert("dsadas");
}

//clear all logs
function clearLogs()
{
    $.ajax({
        url: "queries/clearLogs.php",
        success: function(accountStatus){
            location.reload();
            alert(accountStatus)
        }
    })
}