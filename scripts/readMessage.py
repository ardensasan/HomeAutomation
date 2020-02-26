import serial
import RPi.GPIO as GPIO      
import os, time
import subprocess
import datetime
import mysql.connector
import urllib.request

hasRead = 1;
serialStatus = 0;
cMessageSent = True;
dcMessageSent = False

def turnON(applianceID,phoneNumber):
    global hasRead;
    hasRead = 1;

    print(phoneNumber)
    dateTime = datetime.datetime.now()
    getTurnONUser = connection.cursor()
    getTurnONUser.execute("SELECT userType,userID FROM tbl_users WHERE userPhoneNumber = %s",(phoneNumber,))
    userData = getTurnONUser.fetchall()
    getTurnONUser.close()
    for userDetails in userData:
        userType = userDetails[0];
        userID = userDetails[1];
        print(userID)
        print("awaw")
        getAppliance = connection.cursor()
        getAppliance.execute("SELECT applianceStatus,applianceName,applianceOutputPin FROM tbl_appliances WHERE applianceName IS NOT NULL AND applianceID = %s",(applianceID,))
        applianceData = getAppliance.fetchall()
        getAppliance.close()
        for applianceDetail in applianceData:
            applianceStatus = applianceDetail[0];
            applianceName = applianceDetail[1];
            applianceOutputPin = applianceDetail[2];
            if(applianceStatus != 3):
                updateStatus = connection.cursor()
                updateStatus.execute("UPDATE tbl_appliances SET applianceStatus = 1 WHERE applianceID = %s",(applianceID,))
                connection.commit()
                updateStatus.close()
                insertLog = connection.cursor()
                insertLog.execute("INSERT INTO tbl_logs(logDateTime,logAppliance,logAction,logVia,logUser) VALUES (%s,%s,%s,%s,%s)",(dateTime,applianceName,1,3,int(userID)))
                connection.commit()
                insertLog.close()
                subprocess.call(['python3', '/var/www/html/scripts/turnON.py', str(applianceOutputPin),])
    deleteMessage()
        
def turnOFF(applianceID,phoneNumber):
    global hasRead;
    hasRead = 1;

    dateTime = datetime.datetime.now()
    turnO = connection.cursor()
    turnO.execute("SELECT userType,userID FROM tbl_users WHERE userPhoneNumber = %s",(phoneNumber,))
    userTurnOFF = turnO.fetchall()
    turnO.close()
    for userDetails in userTurnOFF:
        userType = userDetails[0];
        userID = userDetails[1];
    
    turnOApp = connection.cursor()
    turnOApp.execute("SELECT applianceStatus,applianceName,applianceOutputPin FROM tbl_appliances WHERE applianceName IS NOT NULL AND applianceID = %s",(applianceID,))
    turnOFFAppliance = turnOApp.fetchall()
    turnOApp.close()
    for applianceDetail in turnOFFAppliance:
        applianceStatus = applianceDetail[0];
        applianceName = applianceDetail[1];
        applianceOutputPin = applianceDetail[2];
        if(applianceStatus != 3):
            updateStatus = connection.cursor()
            updateStatus.execute("UPDATE tbl_appliances SET applianceStatus = 0 WHERE applianceID = %s",(applianceID,))
            connection.commit()
            updateStatus.close()
            insertLog = connection.cursor()
            insertLog.execute("INSERT INTO tbl_logs(logDateTime,logAppliance,logAction,logVia,logUser) VALUES (%s,%s,%s,%s,%s)",(dateTime,applianceName,0,3,int(userID)))
            connection.commit()
            insertLog.close()
            subprocess.call(['python3', '/var/www/html/scripts/turnOFF.py', str(applianceOutputPin),])
    deleteMessage();
        
def getStatus(phoneNumber):
    global hasRead;
    hasRead = 1;

    userID = None;
    message = "";
    dateTime = datetime.datetime.now()
    getStatusUser = connection.cursor()
    getStatusUser.execute("SELECT userType,userID FROM tbl_users WHERE userPhoneNumber = %s",(phoneNumber,))
    userDStatus = getStatusUser.fetchall()
    getStatusUser.close()
    for userDetails in userDStatus:
        userType = userDetails[0];
        userID = userDetails[1];
    
    if(userID != None):
        getApplianceDetails = connection.cursor()
        getApplianceDetails.execute("SELECT applianceID,applianceName,appliancestatus FROM tbl_appliances WHERE applianceName IS NOT NULL")
        getStatusDAppliance = getApplianceDetails.fetchall()
        getApplianceDetails.close()
        for applianceDetais in getStatusDAppliance:
            message += "Port "+str(applianceDetais[0])+": "+str(applianceDetais[1]) +" - Status : ";
            if(applianceDetais[2] == 0):
                status = "Turned Off\n"
            elif(applianceDetais[2] == 1):
                status = "Turned On\n"
            elif(applianceDetais[2] == 2):
                status = "Disabled\n"
            message += status;   
        phoneNumber = "0" + phoneNumber;
        subprocess.call(['python', '/var/www/html/scripts/sendMessage.py', str(message),str(phoneNumber)])
    deleteMessage();
        
def getLogs(phoneNumber):        
    global hasRead;
    hasRead = 1;
    userID = None;
    message = "";
    dateTime = datetime.datetime.now()
    getLogsUser = connection.cursor()
    getLogsUser.execute("SELECT userType,userID FROM tbl_users WHERE userPhoneNumber = %s",(phoneNumber,))
    getLogUserD = getLogsUser.fetchall()
    getLogsUser.close()
    for userDetails in getLogUserD:
        userType = userDetails[0];
        userID = userDetails[1];
    if(userID != None):
        getLogsList = connection.cursor()
        getLogsList.execute("SELECT CONCAT(tbl_users.userFirstName,' ',tbl_users.userLastName) AS FullName,tbl_logs.logID, DATE_FORMAT(DATE((tbl_logs.logDateTime)),'%M %d %Y') as D, TIME_FORMAT((TIME(tbl_logs.logDateTime)),'%h:%i %p') as T, tbl_logs.logAppliance, tbl_logs.logAction, tbl_logs.logVia FROM tbl_logs JOIN tbl_users ON tbl_logs.logUser=tbl_users.userID ORDER BY tbl_logs.logID DESC LIMIT 5")
        getLogUserDetails = getLogsList.fetchall()
        getLogsList.close()
        for logDetails in getLogUserDetails:
            FullName = str(logDetails[0]);
            Date = str(logDetails[1]);
            Time = str(logDetails[2]);
            ApplianceName = str(logDetails[3]);
            Action = str(logDetails[4]);
            Via = str(logDetails[5]);
            message += ApplianceName+" "+Action+" "+Date+" "+Time+"\n";
        print(message)
        phoneNumber = "0" + phoneNumber;
        subprocess.call(['python', '/var/www/html/scripts/sendMessage.py', str(message),str(phoneNumber)])
    deleteMessage();

def deleteMessage():
    print("delete")
    deleteMessage=serial.Serial('/dev/ttyAMA0',9600,timeout=1);
    deleteMessage.close()
    deleteMessage.open()
    #print line;
    deleteMessage.write('AT+CMGL="ALL"\r')
    response = deleteMessage.read(size=2000);
    print(response)
    #+CMGR: "REC UNREAD","+639956139395","","19/12/31,21:00:05+32"
    #print response[24:34]; #get number of read message
    #print response[26:36]; #get number of unread message
    deleteMessage.write('AT+CMGD=1\r') #Delete message
    deleteMessage.read()
    deleteMessage.close()

def connect():
    try:
        urllib.request.urlopen('http://google.com') #Python 3.x
        return True
    except:
        return False    

while True:
    global hasRead;
    connection = mysql.connector.connect(host="localhost", user="abaynfriends", passwd="abaynfriends", database="homeautomation"
)
    print(hasRead)
    # Enable Serial Communication
    #GLOBE = 9163927131
    # Transmitting AT Commands to the Modem
    # '\r\n' indicates the Enter key
    if(hasRead == 1):
        hasRead = 0;
            
    elif(hasRead == 0):
        if connect():
            #connected message
            if cMessageSent == False:
                #code for sending message here
                cMessageSent = True;
                dcMessageSent = False;
                print("connected");
                notifMessage = "RPi internet connection has been restored";
                subprocess.call(['python', '/var/www/html/scripts/sendMessageToAll.py', str(notifMessage),])
        else:
            #disconnected message
            if dcMessageSent == False:
                #code for sending message here
                cMessageSent = False;
                dcMessageSent = True;
                print("discconnected");
                notifMessage = "RPi is disconnected";
                subprocess.call(['python', '/var/www/html/scripts/sendMessageToAll.py', str(notifMessage),])
        getUnsent = connection.cursor()
        getUnsent.execute("SELECT notifID,notifMessage FROM tbl_unsentNotif")
        getUnsentNotif = getUnsent.fetchall()
        getUnsent.close()
        for unsentNotif in getUnsentNotif:
            notifID = unsentNotif[0];
            notifMessage = unsentNotif[1];
            print("send message all");
            deleteUnsent = connection.cursor()
            deleteUnsent.execute("DELETE FROM tbl_unsentNotif WHERE notifID = %s",(notifID,))
            connection.commit()
            deleteUnsent.close()
            subprocess.call(['python', '/var/www/html/scripts/sendMessageToAll.py', str(notifMessage),])
            
        time.sleep(1)
        
        readMessage=serial.Serial('/dev/ttyAMA0',9600,timeout=1);
        readMessage.close()
        readMessage.open()
        readMessage.write('AT+CMGR=1\r')
        response=readMessage.read(size=2000);
        print(response)
        readMessage.close();
        #+CMGR: "REC UNREAD","+639956139395","","19/12/31,21:00:05+32"
        #print response[24:34]; #get number of read message
        #print response[26:36]; #get number of unread message
        phoneNumber = response[26:36];
        userType = None;
        userID = None;
        
        if "TURN ON 1" in response:
            turnON(1,phoneNumber);
        if "TURN ON 2" in response:
            turnON(2,phoneNumber);
        if "TURN ON 3" in response:
            turnON(3,phoneNumber);
        if "TURN ON 4" in response:
            turnON(4,phoneNumber);
        if "TURN OFF 1" in response:
            turnOFF(1,phoneNumber);
        if "TURN OFF 2" in response:
            turnOFF(2,phoneNumber);
        if "TURN OFF 3" in response:
            turnOFF(3,phoneNumber);
        if "TURN OFF 4" in response:
            turnOFF(4,phoneNumber);
            
        if "LOGS" in response:
            getLogs(phoneNumber);
            
        if "STATUS" in response:
            phoneNumber = phoneNumber;
            getStatus(phoneNumber)
        time.sleep(1)
