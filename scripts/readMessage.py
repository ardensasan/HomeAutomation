import serial
import RPi.GPIO as GPIO      
import os, time
import subprocess
import datetime
import mysql.connector
 
hasRead = 1;

def turnON(applianceID,phoneNumber):
    global hasRead;
    hasRead = 1;
    connection = mysql.connector.connect(
  host="localhost",
  user="abaynfriends",
  passwd="abaynfriends",
  database="homeautomation"
)
    dateTime = datetime.datetime.now()
    getUser = connection.cursor()
    getUser.execute("SELECT userType,userID FROM tbl_users WHERE userPhoneNumber = %s",(phoneNumber,))
    for userDetails in getUser:
        userType = userDetails[0];
        userID = userDetails[1];
    getUser.close()
    getAppliance = connection.cursor()
    getAppliance.execute("SELECT applianceStatus,applianceName,applianceOutputPin FROM tbl_appliances WHERE applianceName IS NOT NULL AND applianceID = %s",(applianceID,))
    for applianceDetail in getAppliance:
        applianceStatus = applianceDetail[0];
        applianceName = applianceDetail[1];
        applianceOutputPin = applianceDetail[2];
    getAppliance.close()
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
        
def turnOFF(applianceID,phoneNumber):
    global hasRead;
    hasRead = 1;
    connection = mysql.connector.connect(
  host="localhost",
  user="abaynfriends",
  passwd="abaynfriends",
  database="homeautomation"
)
    dateTime = datetime.datetime.now()
    getUser = connection.cursor()
    print(phoneNumber)
    getUser.execute("SELECT userType,userID FROM tbl_users WHERE userPhoneNumber = %s",(phoneNumber,))
    for userDetails in getUser:
        print(userDetails)
        userType = userDetails[0];
        userID = userDetails[1];
    getUser.close()
    getAppliance = connection.cursor()
    getAppliance.execute("SELECT applianceStatus,applianceName,applianceOutputPin FROM tbl_appliances WHERE applianceName IS NOT NULL AND applianceID = %s",(applianceID,))
    for applianceDetail in getAppliance:
        applianceStatus = applianceDetail[0];
        applianceName = applianceDetail[1];
        applianceOutputPin = applianceDetail[2];
    getAppliance.close()
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
        
def getStatus(phoneNumber):
    global hasRead;
    hasRead = 1;
    connection = mysql.connector.connect(
  host="localhost",
  user="abaynfriends",
  passwd="abaynfriends",
  database="homeautomation"
)
    print(phoneNumber)
    userID = None;
    message = "";
    dateTime = datetime.datetime.now()
    getUser = connection.cursor()
    getUser.execute("SELECT userType,userID FROM tbl_users WHERE userPhoneNumber = %s",(phoneNumber,))
    for userDetails in getUser:
        userType = userDetails[0];
        userID = userDetails[1];
    getUser.close()
    if(userID != None):
        getApplianceDetails = connection.cursor()
        getApplianceDetails.execute("SELECT applianceID,applianceName,appliancestatus FROM tbl_appliances WHERE applianceName IS NOT NULL")
        for applianceDetais in getApplianceDetails:
            message += "Port "+str(applianceDetais[0])+": "+str(applianceDetais[1]) +" - Status : ";
            if(applianceDetais[2] == 0):
                status = "Turned Off\n"
            elif(applianceDetais[2] == 1):
                status = "Turned On\n"
            elif(applianceDetais[2] == 2):
                status = "Disabled\n"
            message += status;   
        getApplianceDetails.close()
        phoneNumber = "0" + phoneNumber;
        subprocess.call(['python', '/var/www/html/scripts/sendMessage.py', str(message),str(phoneNumber)])
        
def getLogs(phoneNumber):        
    global hasRead;
    hasRead = 1;
    connection = mysql.connector.connect(
  host="localhost",
  user="abaynfriends",
  passwd="abaynfriends",
  database="homeautomation"
)
    print(phoneNumber)
    userID = None;
    message = "";
    dateTime = datetime.datetime.now()
    getUser = connection.cursor()
    getUser.execute("SELECT userType,userID FROM tbl_users WHERE userPhoneNumber = %s",(phoneNumber,))
    for userDetails in getUser:
        userType = userDetails[0];
        userID = userDetails[1];
    getUser.close()
    if(userID != None):
        getLogsList = connection.cursor()
        getLogsList.execute("SELECT CONCAT(tbl_users.userFirstName,' ',tbl_users.userLastName) AS FullName,tbl_logs.logID, DATE_FORMAT(DATE((tbl_logs.logDateTime)),'%M %d %Y') as D, TIME_FORMAT((TIME(tbl_logs.logDateTime)),'%h:%i %p') as T, tbl_logs.logAppliance, tbl_logs.logAction, tbl_logs.logVia FROM tbl_logs JOIN tbl_users ON tbl_logs.logUser=tbl_users.userID ORDER BY tbl_logs.logID DESC LIMIT 5")
        for logDetails in getLogsList:
            FullName = str(logDetails[0]);
            Date = str(logDetails[1]);
            Time = str(logDetails[2]);
            ApplianceName = str(logDetails[3]);
            Action = str(logDetails[4]);
            Via = str(logDetails[5]);
            message += ApplianceName+" "+Action+" "+Date+" "+Time+"\n";
        getLogsList.close()
        phoneNumber = "0" + phoneNumber;
        subprocess.call(['python', '/var/www/html/scripts/sendMessage.py', str(message),str(phoneNumber)])
        
        
while True:
    global hasRead;
    print(hasRead)
    # Enable Serial Communication

    #GLOBE = 9163927131
    # Transmitting AT Commands to the Modem
    # '\r\n' indicates the Enter key
    if(hasRead == 1):
        hasRead = 0;
        deleteMessage=serial.Serial('/dev/ttyAMA0',9600,timeout=1);
        deleteMessage.write("AT\r");
        deleteMessage.read();
        #print line;
        deleteMessage.write('AT+CMGL="ALL"\r')
        response = deleteMessage.read(size=2000);
        print(response)
        #+CMGR: "REC UNREAD","+639956139395","","19/12/31,21:00:05+32"
        #print response[24:34]; #get number of read message
        #print response[26:36]; #get number of unread message
        print(response)
        deleteMessage.write('AT+CMGD=1\r') #Delete message
        deleteMessage.read();
        deleteMessage.close()
    else:
        ser=serial.Serial('/dev/ttyAMA0',9600,timeout=1);
        ser.write("AT\r");
        ser.read();
        #print line;
        ser.write('AT+CMGR=1\r')
        response=ser.read(size=2000);
        ser.close();
        #+CMGR: "REC UNREAD","+639956139395","","19/12/31,21:00:05+32"
        #print response[24:34]; #get number of read message
        #print response[26:36]; #get number of unread message
        phoneNumber = response[31:41];
        print(response)
        print(phoneNumber)
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
            turnON(2,phoneNumber);
        if "TURN OFF 3" in response:
            turnOFF(3,phoneNumber);
        if "TURN OFF 4" in response:
            turnOFF(4,phoneNumber);
            
        if "LOGS" in response:
            getLogs(phoneNumber);
            
        if "STATUS" in response:
            phoneNumber = phoneNumber;
            getStatus(phoneNumber)
    
    time.sleep(5);