#!/usr/bin/env python2.7

import subprocess
import datetime
import time
import RPi.GPIO as GPIO
import mysql.connector
from time import sleep

GPIO.setmode(GPIO.BOARD)

A1 = 37;
A2 = 35;
A3 = 33;
A4 = 31;

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
    connection = mysql.connector.connect(host="localhost", user="abaynfriends", passwd="abaynfriends", database="homeautomation")
    dateTime = datetime.datetime.now()
    applianceStatus = 3;
    applianceID = 0;
    applianceOutputPin = 100;
    applianceName  = None;
    #appliance 1
    # if we're here, an edge was recognized
    sleep(0.005) # debounce for 5mSec
    # only show valid edges
    print(channel)
    if GPIO.input(channel) == 0:
        turnONOFFAppliance = connection.cursor()
        turnONOFFAppliance.execute("SELECT applianceID,applianceName,applianceStatus,applianceOutputPin FROM tbl_appliances WHERE applianceInputPin = %s AND applianceName IS NOT NULL AND applianceStatus != 3",(channel,))
        turnONOFFDAppliance = turnONOFFAppliance.fetchall()
        turnONOFFAppliance.close()
        for turnONOFFDetails in turnONOFFDAppliance:
            applianceID = turnONOFFDetails[0];
            applianceName = turnONOFFDetails[1];
            applianceStatus = turnONOFFDetails[2];
            applianceOutputPin = turnONOFFDetails[3];
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
            
GPIO.add_event_detect(A1, GPIO.FALLING, callback=turnONOFF,bouncetime=1000)
GPIO.add_event_detect(A2, GPIO.FALLING, callback=turnONOFF,bouncetime=1000)
GPIO.add_event_detect(A3, GPIO.FALLING, callback=turnONOFF,bouncetime=1000)
GPIO.add_event_detect(A4, GPIO.FALLING, callback=turnONOFF,bouncetime=1000)
try:
    while True:
        time.sleep(1) #you can put every value of sleep you want here..

except KeyboardInterrupt:  
    GPIO.cleanup()
    

#if __name__ == '__main__':
#    main()
