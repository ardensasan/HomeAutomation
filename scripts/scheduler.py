#!/usr/bin/env python
import subprocess
import datetime
from datetime import timedelta
import time
import RPi.GPIO as GPIO
import mysql.connector
while True:
    connection = mysql.connector.connect(
    host="localhost",
    user="abaynfriends",
    passwd="abaynfriends",
    database="homeautomation"
    )
    dateToday = datetime.datetime.now().strftime('%Y-%m-%d')
    timeToday = datetime.datetime.now().strftime('%H:%M')
    d = (datetime.datetime.now() - timedelta(days=1,hours=0,minutes=1)).strftime('%H:%M')
    scheduler = connection.cursor()
    scheduler.execute("SELECT tbl_appliances.applianceID,tbl_appliances.applianceName,tbl_schedules.scheduleDate,TIME_FORMAT(tbl_schedules.scheduleTime, '%H:%i') AS `Time`, tbl_appliances.applianceOutputPin,tbl_schedules.scheduleAction,tbl_schedules.scheduleRepeat,tbl_schedules.scheduleID,tbl_schedules.isExecuted FROM tbl_schedules LEFT JOIN tbl_appliances ON tbl_schedules.scheduleApplianceID=tbl_appliances.applianceID")
    data = scheduler.fetchall()
    scheduler.close()
    for row in data:
        applianceID = None;
        applianceName = None;
        scheduleDate = None;
        scheduleTime = None;
        applianceOutputPin = None;
        scheduleAction = None;
        scheduleRepeat = None;
        scheduleID = None;
        applianceID = row[0]
        applianceName = row[1]
        scheduleDate = str(row[2])
        scheduleTime = str(row[3])
        applianceOutputPin = row[4]
        scheduleAction = row[5]
        scheduleRepeat = row[6]
        scheduleID = row[7]
        isExecuted = row[8]
        if scheduleRepeat == '':
            if scheduleDate == dateToday and scheduleTime == timeToday:
                if scheduleAction == 0:
                    schedulerAction = connection.cursor()
                    schedulerAction.execute("UPDATE tbl_appliances SET applianceStatus = 0 WHERE applianceID = %s",(applianceID,))
                    connection.commit()
                    schedulerAction.close()
                    schedulerAction = connection.cursor()
                    schedulerAction.execute("DELETE FROM tbl_schedules WHERE scheduleID = %s",(scheduleID,))
                    connection.commit()
                    schedulerAction.close()
                    schedulerAction = connection.cursor()
                    schedulerAction.execute("INSERT INTO tbl_logs(logDateTime,logAppliance,logAction,logVia,logUser) VALUES (%s,%s,%s,%s,%s)",(dateToday+" "+timeToday,applianceName,0,2,1))
                    connection.commit() 
                    schedulerAction.close()
                    subprocess.call(['python3', '/var/www/html/scripts/turnOFF.py', str(applianceOutputPin),])
                elif scheduleAction == 1:
                    schedulerAction = connection.cursor()
                    schedulerAction.execute("UPDATE tbl_appliances SET applianceStatus = 1 WHERE applianceID = %s",(applianceID,))
                    connection.commit()
                    schedulerAction.close()
                    schedulerAction = connection.cursor()
                    schedulerAction.execute("DELETE FROM tbl_schedules WHERE scheduleID = %s",(scheduleID,))
                    connection.commit()
                    schedulerAction.close()
                    schedulerAction = connection.cursor()
                    schedulerAction.execute("INSERT INTO tbl_logs(logDateTime,logAppliance,logAction,logVia,logUser) VALUES (%s,%s,%s,%s,%s)",(dateToday+" "+timeToday,applianceName,1,2,1))
                    connection.commit()
                    schedulerAction.close()
                    subprocess.call(['python3', '/var/www/html/scripts/turnON.py', str(applianceOutputPin),])
                    
        else:
            #execute repeated schedule
            count = -1;
            dayToday = datetime.datetime.now().weekday()
            for day in scheduleRepeat:
                count = count + 1;
                if day == "1":
                    if count == dayToday and scheduleTime == timeToday and isExecuted == 0:
                        if isExecuted == 0:
                            schedulerAction = connection.cursor()
                            schedulerAction.execute("UPDATE tbl_schedules SET isExecuted = 1 WHERE scheduleID = %s",(scheduleID,))
                            connection.commit()
                            schedulerAction.close()
                            if scheduleAction == 0:
                                schedulerAction = connection.cursor()
                                schedulerAction.execute("UPDATE tbl_appliances SET applianceStatus = 0 WHERE applianceID = %s",(applianceID,))
                                connection.commit()
                                schedulerAction.close()
                                schedulerAction = connection.cursor()
                                schedulerAction.execute("INSERT INTO tbl_logs(logDateTime,logAppliance,logAction,logVia,logUser) VALUES (%s,%s,%s,%s,%s)",(dateToday+" "+timeToday,applianceName,0,2,1))
                                connection.commit() 
                                schedulerAction.close()
                                subprocess.call(['python3', '/var/www/html/scripts/turnOFF.py', str(applianceOutputPin),])
                            elif scheduleAction == 1:
                                schedulerAction = connection.cursor()
                                schedulerAction.execute("UPDATE tbl_appliances SET applianceStatus = 1 WHERE applianceID = %s",(applianceID,))
                                connection.commit()
                                schedulerAction.close()
                                schedulerAction = connection.cursor()
                                schedulerAction.execute("INSERT INTO tbl_logs(logDateTime,logAppliance,logAction,logVia,logUser) VALUES (%s,%s,%s,%s,%s)",(dateToday+" "+timeToday,applianceName,1,2,1))
                                connection.commit()
                                schedulerAction.close()
                                subprocess.call(['python3', '/var/www/html/scripts/turnON.py', str(applianceOutputPin),])
                    elif count == dayToday and scheduleTime <= d and isExecuted == 1:
                                schedulerAction = connection.cursor()
                                schedulerAction.execute("UPDATE tbl_schedules SET isExecuted = 0 WHERE scheduleID = %s",(scheduleID,))
                                connection.commit()
                                schedulerAction.close()
time.sleep(50)
