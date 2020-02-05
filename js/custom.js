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
                changeText("errorMessage"," ");
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
//add schedule
function addSchedule()
{
    alert("se");
    // $.ajax({
    //     url: "queries/addSchedule.php",
    //     method: "POST",
    //     data: {scheduleDate: scheduleDate,scheduleTime: scheduleTime,scheduleApplianceID:scheduleApplianceID,scheduleAction:scheduleAction},
    //     success: function(){
    //         location.reload();
    //     }
    // })
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
        }
    })
}

//display calibration modal
function calibrateDisplay(applianceID,applianceName)
{
    document.getElementById("calibrateCountdown").innerHTML = '<button type="button" class="btn btn-primary" onclick="calibrateCount()"><span id="calText">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Calibrate&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></button>';
    document.getElementById("calAppID").value = applianceID;
    document.getElementById("calAppName").value = applianceName;
    document.getElementById("calAppliance").innerHTML = applianceName;
    document.getElementById("calMessage").innerHTML = "Turn On Appliance Before Calibrating";
    $('#calibrateModal').modal('show');
}

//edit appliance name modal
function editApplianceDisplay(applianceID,applianceName)
{
    document.getElementById("editAppID").value = applianceID;
    document.getElementById("editedApplianceName").value = applianceName;
    document.getElementById("calMessage").innerHTML = "Turn On Appliance Before Calibrating";
    $('#editApplianceModal').modal('show');
}

//edit appliance name
function editApplianceName()
{
    var applianceID = document.getElementById("editAppID").value
    var applianceName = document.getElementById("editedApplianceName").value;
    if(applianceName == ""){
        alert("Please enter an appliance name");
    }else{
        $.ajax({
            url: "queries/editApplianceName.php",
            method: "POST",
            data: {applianceID:applianceID,applianceName:applianceName},
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
            url: "queries/editApplianceName.php",
            method: "POST",
            data: {applianceID:applianceID,applianceName:applianceName},
            success: function(){
                location.reload();
            }
        })
    }
}

//remove appliance
function removeAppliance(applianceID)
{
    $.ajax({
        url: "queries/removeAppliance.php",
        method: "POST",
        data: {applianceID:applianceID},
        success: function(){
            location.reload();
        }
    })
}

//display calibration modal
function calibrateCount()
{
    var applianceID = document.getElementById("calAppID").value;
    var count = 3;
    document.getElementById("calibrateCountdown").innerHTML = '<button class="btn btn-primary" disabled><span id="calText">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Please Wait '+count+' seconds&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></button>';
    var countDown = setInterval(
        function(){ 
            count--;
            document.getElementById("calibrateCountdown").innerHTML = '<button class="btn btn-primary" disabled><span id="calText">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Please Wait '+count+' seconds&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></button>';
            if(count == 0){
                $.ajax({
                    url: "queries/setRating.php",
                    method: "POST",
                    data: {applianceID:applianceID},
                    dataType: 'JSON',
                    success: function(result){
                        document.getElementById("calMessage").innerHTML = '&nbsp;&nbsp;Average Power: '+result.avg+' W<br>&nbsp;&nbsp;Upper Control Limit: '+result.UCL+' W<br>&nbsp;&nbsp;Lower Control Limit: '+result.LCL+' W';
                    }
                })
                document.getElementById("calibrateCountdown").innerHTML = '<button type="button" class="btn btn-primary" data-dismiss ="modal" onclick="window.location.reload()"><span id="calText">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Calibration Finished&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></button>';
                clearInterval(countDown);
            }
        }, 
    1000);   
}

//display calibration modal
function scheduleDisplayModal(applianceID,applianceName)
{
    document.getElementById("calibrateCountdown").innerHTML = '<button type="button" class="btn btn-primary" onclick="calibrateCount()"><span id="calText">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Calibrate&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></button>';
    document.getElementById("calAppID").value = applianceID;
    document.getElementById("calAppName").value = applianceName;
    document.getElementById("calAppliance").innerHTML = applianceName;
    document.getElementById("calMessage").innerHTML = "Turn On Appliance Before Calibrating";
    $('#calibrateModal').modal('show')
}

function gago(){
    alert("sdadsa");
}