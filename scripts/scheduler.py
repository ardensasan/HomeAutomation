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
while True:
    dateToday = datetime.datetime.now().strftime('%Y-%m-%d')
    timeToday = datetime.datetime.now().strftime('%H:%M')
    result = connection.cursor()
    result.execute("SELECT tbl_appliances.applianceID,tbl_appliances.applianceName,tbl_schedules.scheduleDate,TIME_FORMAT(tbl_schedules.scheduleTime, '%H:%i') AS `Time`, tbl_appliances.applianceOutputPin,tbl_schedules.scheduleAction,tbl_schedules.scheduleRepeat,tbl_schedules.scheduleID FROM tbl_schedules LEFT JOIN tbl_appliances ON tbl_schedules.scheduleApplianceID=tbl_appliances.applianceID")
    data = result.fetchall()
    for row in data:
        applianceID = row[0];
        applianceName = row[1];
        scheduleDate = str(row[2]);
        scheduleTime = str(row[3]);
        applianceOutputPin = row[4];
        scheduleAction = row[5];
        scheduleRepeat = row[6];
        scheduleID = row[7];
        if scheduleRepeat == None:
            if scheduleDate == dateToday and scheduleTime == timeToday:
                if scheduleAction == 0:
                    cursor = connection.cursor()
                    cursor.execute("UPDATE tbl_appliances SET applianceStatus = 0 WHERE applianceID = %s",(applianceID,))
                    connection.commit()
                    cursor.close()
                    cursor = connection.cursor()
                    cursor.execute("DELETE FROM tbl_schedules WHERE scheduleID = %s",(scheduleID,))
                    connection.commit()
                    cursor.close()
                    cursor = connection.cursor()
                    cursor.execute("INSERT INTO tbl_logs(logDateTime,logAppliance,logAction,logVia,logUser) VALUES (%s,%s,%s,%s,%s)",(dateToday+" "+timeToday,applianceName,0,2,1))
                    connection.commit()	
                    cursor.close()
                    subprocess.call(['python3', '/var/www/html/scripts/turnOFF.py', str(applianceOutputPin),])
                if scheduleAction == 1:
                    cursor = connection.cursor()
                    cursor.execute("UPDATE tbl_appliances SET applianceStatus = 1 WHERE applianceID = %s",(applianceID,))
                    connection.commit()
                    cursor.close()
                    cursor = connection.cursor()
                    cursor.execute("DELETE FROM tbl_schedules WHERE scheduleID = %s",(scheduleID,))
                    connection.commit()
                    cursor.close()
                    cursor = connection.cursor()
                    cursor.execute("INSERT INTO tbl_logs(logDateTime,logAppliance,logAction,logVia,logUser) VALUES (%s,%s,%s,%s,%s)",(dateToday+" "+timeToday,applianceName,1,2,1))
                    connection.commit()
                    cursor.close()
                    subprocess.call(['python3', '/var/www/html/scripts/turnON.py', str(applianceOutputPin),])
        else:
            #execute repeated schedule
            count = -1;
            dayToday = datetime.datetime.now().weekday()
            for day in scheduleRepeat:
                count = count + 1;
                if day == "1":
                    if count == dayToday and scheduleTime == timeToday:
                        if scheduleAction == 0:
                            cursor = connection.cursor()
                            cursor.execute("UPDATE tbl_appliances SET applianceStatus = 0 WHERE applianceID = %s",(applianceID,))
                            connection.commit()
                            cursor.close()
                            cursor = connection.cursor()
                            cursor.execute("INSERT INTO tbl_logs(logDateTime,logAppliance,logAction,logVia,logUser) VALUES (%s,%s,%s,%s,%s)",(dateToday+" "+timeToday,applianceName,0,2,1))
                            connection.commit() 
                            cursor.close()
                            subprocess.call(['python3', '/var/www/html/scripts/turnOFF.py', str(applianceOutputPin),])
                        if scheduleAction == 1:
                            cursor = connection.cursor()
                            cursor.execute("UPDATE tbl_appliances SET applianceStatus = 1 WHERE applianceID = %s",(applianceID,))
                            connection.commit()
                            cursor.close()
                            cursor = connection.cursor()
                            cursor.execute("INSERT INTO tbl_logs(logDateTime,logAppliance,logAction,logVia,logUser) VALUES (%s,%s,%s,%s,%s)",(dateToday+" "+timeToday,applianceName,1,2,1))
                            connection.commit()
                            cursor.close()
                            subprocess.call(['python3', '/var/www/html/scripts/turnON.py', str(applianceOutputPin),])

    result.close()
time.sleep(10)







