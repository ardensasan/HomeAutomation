#!/usr/bin/env python2.7

import subprocess
import datetime
import time
import RPi.GPIO as GPIO
import mysql.connector

connection = mysql.connector.connect(host="localhost", user="abaynfriends", passwd="abaynfriends", database="homeautomation")
turnONOFFAppliance = connection.cursor()
turnONOFFAppliance.execute("SELECT applianceStatus,applianceOutputPin FROM tbl_appliances")
turnONOFFDAppliance = turnONOFFAppliance.fetchall()
turnONOFFAppliance.close()
for turnONOFFDetails in turnONOFFDAppliance:
    applianceStatus = turnONOFFDetails[0];
    applianceOutputPin = turnONOFFDetails[1];
    if applianceStatus == 1:
        subprocess.call(['python3', '/var/www/html/scripts/turnON.py', str(applianceOutputPin),])
    else:
        subprocess.call(['python3', '/var/www/html/scripts/turnOFF.py', str(applianceOutputPin),])
