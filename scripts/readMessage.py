import serial
import RPi.GPIO as GPIO      
import os, time
import subprocess
import datetime
import mysql.connector
import re
import urllib2

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
        getAppliance = connection.cursor()
        getAppliance.execute("SELECT applianceStatus,applianceName,applianceOutputPin FROM tbl_appliances WHERE applianceName IS NOT NULL AND applianceID = %s",(applianceID,))
        applianceData = getAppliance.fetchall()
        getAppliance.close()
        for applianceDetail in applianceData:
            applianceStatus = applianceDetail[0];
            applianceName = applianceDetail[1];
            applianceOutputPin = applianceDetail[2];
            if(applianceStatus != 2):
                updateStatus = connection.cursor()
                updateStatus.execute("UPDATE tbl_appliances SET applianceStatus = 1 WHERE applianceID = %s",(applianceID,))
                connection.commit()
                updateStatus.close()
                insertLog = connection.cursor()
                insertLog.execute("INSERT INTO tbl_logs(logDateTime,logAppliance,logAction,logVia,logUser) VALUES (%s,%s,%s,%s,%s)",(dateTime,applianceName,1,3,int(userID)))
                connection.commit()
                insertLog.close()
                subprocess.call(['python3', '/var/www/html/scripts/turnON.py', str(applianceOutputPin),])
                message = "Port " +str(applianceID)+" - Turned on";
                subprocess.call(['python', '/var/www/html/scripts/sendMessage.py', str(message),str(phoneNumber)])
                time.sleep(1)
    deleteMessage()
        
def turnOFF(applianceID,phoneNumber):
    global hasRead;
    hasRead = 1;
    print(phoneNumber)
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
            if(applianceStatus != 2):
                updateStatus = connection.cursor()
                updateStatus.execute("UPDATE tbl_appliances SET applianceStatus = 0 WHERE applianceID = %s",(applianceID,))
                connection.commit()
                updateStatus.close()
                insertLog = connection.cursor()
                insertLog.execute("INSERT INTO tbl_logs(logDateTime,logAppliance,logAction,logVia,logUser) VALUES (%s,%s,%s,%s,%s)",(dateTime,applianceName,0,3,int(userID)))
                connection.commit()
                insertLog.close()
                subprocess.call(['python3', '/var/www/html/scripts/turnOFF.py', str(applianceOutputPin),])
                message = "Port " +str(applianceID)+" - Turned off";
                subprocess.call(['python', '/var/www/html/scripts/sendMessage.py', str(message),str(phoneNumber)])
                time.sleep(1)
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
            message += "P-"+str(applianceDetais[0])+": "+str(applianceDetais[1]) +"-Status: ";
            if(applianceDetais[2] == 0):
                status = "Off\n"
            elif(applianceDetais[2] == 1):
                status = "On\n"
            elif(applianceDetais[2] == 2):
                status = "Disabled\n"
            message += status;   
        phoneNumber = "0" + phoneNumber;
        subprocess.call(['python', '/var/www/html/scripts/sendMessage.py', str(message),str(phoneNumber)])
        time.sleep(1)
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
    time.sleep(1)
    deleteMessage=serial.Serial('/dev/ttyAMA0',9600,timeout=1);
    deleteMessage.close()
    deleteMessage.open()
    #print line;
    deleteMessage.write('AT+CMGL="ALL"\r')
    response = deleteMessage.read(size=2000);
    deleteMessage.write('AT+CMGD=1,4\r') #Delete message
    deleteMessage.read()
    deleteMessage.close()
    time.sleep(2)

def connect():
    try:
        urllib2.urlopen('http://google.com') #Python 3.x
        return True
    except:
        return False    

while True:
    global hasRead;
    connection = mysql.connector.connect(host="localhost", user="abaynfriends", passwd="abaynfriends", database="homeautomation"
)
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
        if "REC UNREAD" in response:
            x = re.findall('[0-9]+', response)
            getLogsUser = connection.cursor()
            getLogsUser.execute("SELECT userPhoneNumber FROM tbl_users")
            getLogUserD = getLogsUser.fetchall()
            getLogsUser.close()

            for number in x:
                for PN in getLogUserD:
                    phoneNumber = PN[0];
                    if phoneNumber in number:
                        userType = None;
                        userID = None;
                        
                        if "TURN ON 1" in response:
                            turnON(1,phoneNumber);
                        elif "TURN ON 2" in response:
                            turnON(2,phoneNumber);
                        elif "TURN ON 3" in response:
                            turnON(3,phoneNumber);
                        elif "TURN ON 4" in response:
                            turnON(4,phoneNumber);
                        elif "TURN OFF 1" in response:
                            turnOFF(1,phoneNumber);
                        elif "TURN OFF 2" in response:
                            turnOFF(2,phoneNumber);
                        elif "TURN OFF 3" in response:
                            turnOFF(3,phoneNumber);
                        elif "TURN OFF 4" in response:
                            turnOFF(4,phoneNumber);
                            
                        elif "LOGS" in response:
                            getLogs(phoneNumber);
                            
                        elif "STATUS" in response:
                            phoneNumber = phoneNumber;
                            getStatus(phoneNumber)
        elif "REC READ" in response:
            deleteMessage();
    time.sleep(1) 
