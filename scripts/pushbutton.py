import mysql.connector
import subprocess
import RPi.GPIO as GPIO
connection = mysql.connector.connect(
  host="localhost",
  user="abaynfriends",
  passwd="abaynfriends",
  database="homeautomation"
)
A1 = 35;
A2 = 37;
A3 = 38;
A4 = 40;
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
    #appliance 1
    if channel==A1:
        result = connection.cursor()
        result.execute("SELECT applianceID,applianceStatus,applianceOutputPin FROM tbl_appliances WHERE applianceInputPin = %s",(A1,))
        for x in result:
            applianceID = x[0];
            applianceStatus = x[1];
            applianceOutputPin = x[2];
        result.close()
        if applianceStatus == 0:
            cursor = connection.cursor()
            cursor.execute("UPDATE tbl_appliances SET applianceStatus = 1 WHERE applianceID = %s",(applianceID,))
            connection.commit()
            cursor.close()
            subprocess.call(['python', 'turnON.py', str(applianceOutputPin),])
        if applianceStatus == 1:
            cursor = connection.cursor()
            cursor.execute("UPDATE tbl_appliances SET applianceStatus = 0 WHERE applianceID = %s",(applianceID,))
            connection.commit()
            cursor.close()
            subprocess.call(['python', 'turnOFF.py', str(applianceOutputPin),])
    #appliance 2
    if channel==A2:
        result = connection.cursor()
        result.execute("SELECT applianceID,applianceStatus,applianceOutputPin FROM tbl_appliances WHERE applianceInputPin = %s",(A2,))
        for x in result:
            applianceID = x[0];
            applianceStatus = x[1];
            applianceOutputPin = x[2];
        result.close()
        if applianceStatus == 0:
            cursor = connection.cursor()
            cursor.execute("UPDATE tbl_appliances SET applianceStatus = 1 WHERE applianceID = %s",(applianceID,))
            connection.commit()
            cursor.close()
            subprocess.call(['python', 'turnON.py', str(applianceOutputPin),])
        if applianceStatus == 1:
            cursor = connection.cursor()
            cursor.execute("UPDATE tbl_appliances SET applianceStatus = 0 WHERE applianceID = %s",(applianceID,))
            connection.commit()
            cursor.close()
            subprocess.call(['python', 'turnOFF.py', str(applianceOutputPin),])
    #appliance 3
    if channel==A3:
        result = connection.cursor()
        result.execute("SELECT applianceID,applianceStatus,applianceOutputPin FROM tbl_appliances WHERE applianceInputPin = %s",(A3,))
        for x in result:
            applianceID = x[0];
            applianceStatus = x[1];
            applianceOutputPin = x[2];
        result.close()
        if applianceStatus == 0:
            cursor = connection.cursor()
            cursor.execute("UPDATE tbl_appliances SET applianceStatus = 1 WHERE applianceID = %s",(applianceID,))
            connection.commit()
            cursor.close()
            print(applianceOutputPin);
            subprocess.call(['python', 'turnON.py', str(applianceOutputPin),])
        if applianceStatus == 1:
            cursor = connection.cursor()
            cursor.execute("UPDATE tbl_appliances SET applianceStatus = 0 WHERE applianceID = %s",(applianceID,))
            connection.commit()
            cursor.close()
            print(applianceOutputPin);
            subprocess.call(['python', 'turnOFF.py', str(applianceOutputPin),])
    #appliance 4
    if channel==A4:
        result = connection.cursor()
        result.execute("SELECT applianceID,applianceStatus,applianceOutputPin FROM tbl_appliances WHERE applianceInputPin = %s",(A4,))
        for x in result:
            applianceID = x[0];
            applianceStatus = x[1];
            applianceOutputPin = x[2];
        result.close()
        if applianceStatus == 0:
            cursor = connection.cursor()
            cursor.execute("UPDATE tbl_appliances SET applianceStatus = 1 WHERE applianceID = %s",(applianceID,))
            connection.commit()
            cursor.close()
            subprocess.call(['python', 'turnON.py', str(applianceOutputPin),])
        if applianceStatus == 1:
            cursor = connection.cursor()
            cursor.execute("UPDATE tbl_appliances SET applianceStatus = 0 WHERE applianceID = %s",(applianceID,))
            connection.commit()
            cursor.close()
            subprocess.call(['python', 'turnOFF.py', str(applianceOutputPin),])
#gpio events for push button            
GPIO.add_event_detect(A1, GPIO.RISING, callback=turnONOFF,bouncetime=5000)
GPIO.add_event_detect(A2, GPIO.RISING, callback=turnONOFF,bouncetime=5000)
GPIO.add_event_detect(A3, GPIO.RISING, callback=turnONOFF,bouncetime=5000)
GPIO.add_event_detect(A4, GPIO.RISING, callback=turnONOFF,bouncetime=5000)








