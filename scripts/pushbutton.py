#!/usr/bin/env python
import subprocess
import datetime
import time
import RPi.GPIO as GPIO
import mysql.connector
connection = mysql.connector.connect(
  host="localhost",
  user="abaynfriends",
  passwd="abaynfriends",
  database="homeautomation"
)
A1 = 40;
A2 = 38;
A3 = 36;
A4 = 32;
#set pins with pullup resistors
GPIO.setmode(GPIO.BOARD)
GPIO.setup(A1,GPIO.IN,pull_up_down=GPIO.PUD_UP)
GPIO.setmode(GPIO.BOARD)
GPIO.setup(A2,GPIO.IN,pull_up_down=GPIO.PUD_UP)
GPIO.setmode(GPIO.BOARD)
GPIO.setup(A3,GPIO.IN,pull_up_down=GPIO.PUD_UP)
GPIO.setmode(GPIO.BOARD)
GPIO.setup(A4,GPIO.IN,pull_up_down=GPIO.PUD_UP)

def turnONOFF(channel):
    dateTime = datetime.datetime.now()
    #appliance 1
    if channel==A1:

        result = connection.cursor()
        result.execute("SELECT applianceID,applianceName,applianceStatus,applianceOutputPin FROM tbl_appliances WHERE applianceInputPin = %s AND WHERE applianceName != %s AND WHERE applianceStatus != %s",(A1,NULL,3))
        for x in result:
            applianceID = x[0];
            applianceName = x[1];
            applianceStatus = x[2];
            applianceOutputPin = x[3];
        result.close()
        if applianceStatus == 0:
            cursor = connection.cursor()
            cursor.execute("UPDATE tbl_appliances SET applianceStatus = 1 WHERE applianceID = %s",(applianceID,))
            connection.commit()
            cursor.close()
            cursor = connection.cursor()
            cursor.execute("INSERT INTO tbl_logs(logDateTime,logAppliance,logAction,logVia,logUser) VALUES (%s,%s,%s,%s,%s)",(dateTime,applianceName,1,1,1))
            connection.commit()
            cursor.close()
            subprocess.call(['python3', '/var/www/html/scripts/turnON.py', str(applianceOutputPin),])
        if applianceStatus == 1:
            cursor = connection.cursor()
            cursor.execute("UPDATE tbl_appliances SET applianceStatus = 0 WHERE applianceID = %s",(applianceID,))
            connection.commit()
            cursor.close()
            cursor = connection.cursor()
            cursor.execute("INSERT INTO tbl_logs(logDateTime,logAppliance,logAction,logVia,logUser) VALUES (%s,%s,%s,%s,%s)",(dateTime,applianceName,0,1,1))
            connection.commit()
            cursor.close()
            subprocess.call(['python3', '/var/www/html/scripts/turnOFF.py', str(applianceOutputPin),])
    #appliance 2
    if channel==A2:
        result = connection.cursor()
        result.execute("SELECT applianceID,applianceName,applianceStatus,applianceOutputPin FROM tbl_appliances WHERE applianceInputPin = %s AND WHERE applianceName != %s AND WHERE applianceStatus != %s",(A2,NULL,3))
        for x in result:
            applianceID = x[0];
            applianceName = x[1];
            applianceStatus = x[2];
            applianceOutputPin = x[3];
        result.close()
        if applianceStatus == 0:
            cursor = connection.cursor()
            cursor.execute("UPDATE tbl_appliances SET applianceStatus = 1 WHERE applianceID = %s",(applianceID,))
            connection.commit()
            cursor.close()
            cursor = connection.cursor()
            cursor.execute("INSERT INTO tbl_logs(logDateTime,logAppliance,logAction,logVia,logUser) VALUES (%s,%s,%s,%s,%s)",(dateTime,applianceName,1,1,1))
            connection.commit()
            cursor.close()
            subprocess.call(['python3', '/var/www/html/scripts/turnON.py', str(applianceOutputPin),])
        if applianceStatus == 1:
            cursor = connection.cursor()
            cursor.execute("UPDATE tbl_appliances SET applianceStatus = 0 WHERE applianceID = %s",(applianceID,))
            connection.commit()
            cursor.close()
            cursor = connection.cursor()
            cursor.execute("INSERT INTO tbl_logs(logDateTime,logAppliance,logAction,logVia,logUser) VALUES (%s,%s,%s,%s,%s)",(dateTime,applianceName,0,1,1))
            connection.commit()
            cursor.close()
            subprocess.call(['python3', '/var/www/html/scripts/turnOFF.py', str(applianceOutputPin),])
    #appliance 3
    if channel==A3:
        result = connection.cursor()
        result.execute("SELECT applianceID,applianceName,applianceStatus,applianceOutputPin FROM tbl_appliances WHERE applianceInputPin = %s AND WHERE applianceName != %s AND WHERE applianceStatus != %s",(A3,NULL,3))
        for x in result:
            applianceID = x[0];
            applianceName = x[1];
            applianceStatus = x[2];
            applianceOutputPin = x[3];
        result.close()
        if applianceStatus == 0:
            cursor = connection.cursor()
            cursor.execute("UPDATE tbl_appliances SET applianceStatus = 1 WHERE applianceID = %s",(applianceID,))
            connection.commit()
            cursor.close()
            cursor = connection.cursor()
            cursor.execute("INSERT INTO tbl_logs(logDateTime,logAppliance,logAction,logVia,logUser) VALUES (%s,%s,%s,%s,%s)",(dateTime,applianceName,1,1,1))
            connection.commit()
            cursor.close()
            subprocess.call(['python3', '/var/www/html/scripts/turnON.py', str(applianceOutputPin),])
        if applianceStatus == 1:
            cursor = connection.cursor()
            cursor.execute("UPDATE tbl_appliances SET applianceStatus = 0 WHERE applianceID = %s",(applianceID,))
            connection.commit()
            cursor.close()
            cursor = connection.cursor()
            cursor.execute("INSERT INTO tbl_logs(logDateTime,logAppliance,logAction,logVia,logUser) VALUES (%s,%s,%s,%s,%s)",(dateTime,applianceName,0,1,1))
            connection.commit()
            cursor.close()
            subprocess.call(['python3', '/var/www/html/scripts/turnOFF.py', str(applianceOutputPin),])
    #appliance 4
    if channel==A4:
        result = connection.cursor()
        result.execute("SELECT applianceID,applianceName,applianceStatus,applianceOutputPin FROM tbl_appliances WHERE applianceInputPin = %s AND WHERE applianceName != %s AND WHERE applianceStatus != %s",(A4,NULL,3))
        for x in result:   
            applianceID = x[0];
            applianceName = x[1];
            applianceStatus = x[2];
            applianceOutputPin = x[3];
        result.close()
        if applianceStatus == 0:
            cursor = connection.cursor()
            cursor.execute("UPDATE tbl_appliances SET applianceStatus = 1 WHERE applianceID = %s",(applianceID,))
            connection.commit()
            cursor.close()
            cursor = connection.cursor()
            cursor.execute("INSERT INTO tbl_logs(logDateTime,logAppliance,logAction,logVia,logUser) VALUES (%s,%s,%s,%s,%s)",(dateTime,applianceName,1,1,1))
            connection.commit()
            cursor.close()
            subprocess.call(['python3', '/var/www/html/scripts/turnON.py', str(applianceOutputPin),])
        if applianceStatus == 1:
            cursor = connection.cursor()
            cursor.execute("UPDATE tbl_appliances SET applianceStatus = 0 WHERE applianceID = %s",(applianceID,))
            connection.commit()
            cursor.close()
            cursor = connection.cursor()
            cursor.execute("INSERT INTO tbl_logs(logDateTime,logAppliance,logAction,logVia,logUser) VALUES (%s,%s,%s,%s,%s)",(dateTime,applianceName,0,1,1))
            connection.commit()
            cursor.close()
            subprocess.call(['python3', '/var/www/html/scripts/turnOFF.py', str(applianceOutputPin),])
#gpio events for push button            
GPIO.add_event_detect(A1, GPIO.RISING, callback=turnONOFF,bouncetime=2000)
GPIO.add_event_detect(A2, GPIO.RISING, callback=turnONOFF,bouncetime=2000)
GPIO.add_event_detect(A3, GPIO.RISING, callback=turnONOFF,bouncetime=2000)
GPIO.add_event_detect(A4, GPIO.RISING, callback=turnONOFF,bouncetime=2000)


try:
    while True:
        time.sleep(60) #you can put every value of sleep you want here..

except KeyboardInterrupt:  
    GPIO.cleanup() 





