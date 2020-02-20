import time
import Adafruit_ADS1x15
import math
import mysql.connector
import datetime
import subprocess

adc = Adafruit_ADS1x15.ADS1115(address=0x48)
adc1 = Adafruit_ADS1x15.ADS1115(address=0x49)
adc.data_rate = 860;
adc1.data_rate = 860;
GAIN = 1
samples = 10;

lowLCL = "Wattage is lower than accepted levels";
highUCL = "Wattage is higher than accepted levels";

lCounter = [0,0,0,0];
hCounter = [0,0,0,0];
fallingCounter1 = 0;
fallingCounter2 = 0;
fallingCounter3 = 0;
fallingCounter4 = 0;
fallingCounter5 = 0;

def getCurrent(value):
    if(value <= 2):
        value = 0;
    value = round(((value*0.181)/255),3)
    return value;

def getVoltage(value):
    value = value/samples
    if(value < 15000):
        value = 0;
    else:
        value = round((math.sqrt(value) / 30.5 * 5 / 0.105),0);
    return value;

def saveReadings(current,voltage,applianceID):
    connection = mysql.connector.connect(
  host="localhost",
  user="abaynfriends",
  passwd="abaynfriends",
  database="homeautomation"
)
    dateLimit = datetime.datetime.now()-datetime.timedelta(minutes=10);
    dateTime = datetime.datetime.now();
    dateLimit = dateLimit.replace(microsecond=0);
    
    cursor = connection.cursor()
    cursor.execute("INSERT INTO tbl_readings(applianceID,rCurrent,rVoltage,rDateTime) VALUES (%s,%s,%s,%s)",(applianceID,current,voltage,dateTime))
    connection.commit()
    cursor.close()
    
    cursor = connection.cursor()
    query = 'DELETE FROM tbl_readings WHERE rDateTime < "'+str(dateLimit)+'"';
    cursor.execute(query);
    connection.commit()
    cursor.close()    
    
def saveLowNotification(wattage,applianceID,applianceName):
    connection = mysql.connector.connect(
  host="localhost",
  user="abaynfriends",
  passwd="abaynfriends",
  database="homeautomation"
)
    notifMessage = "Abnormal watt readings on Port "+str(applianceID)+" "+str(applianceName);
    notifText = str(wattage)+" W lower than acceptable levels";
    result = connection.cursor()
    result.execute("INSERT INTO tbl_notifications(notifMessage,notifText,notifDateTime) VALUES (%s,%s,%s)",(notifMessage,notifText,dateTime))
    result.lastrowid
    result.execute('SELECT last_insert_id()')
    lastID = result.fetchone()
    connection.commit()
    result.close()
    
    subprocess.call(['python', '/var/www/html/scripts/sendMessageToAll.py', str(notifMessage),])
    
    result = connection.cursor()
    result.execute("SELECT userID FROM tbl_users");
    myresult = result.fetchall()
    for userID in myresult:
        saveNotif = connection.cursor()
        saveNotif.execute("INSERT INTO tbl_notification_status(notifUserID,notifID,notifStatus) VALUES (%s,%s,%s)",(userID[0],lastID[0],0))
        connection.commit()
        saveNotif.close()
    result.close()
    
def saveHighNotification(wattage,applianceID,applianceName):
    connection = mysql.connector.connect(
  host="localhost",
  user="abaynfriends",
  passwd="abaynfriends",
  database="homeautomation"
)
    notifMessage = "Abnormal watt readings on Port "+str(applianceID)+" "+str(applianceName);
    notifText = str(wattage)+" W lower than acceptable levels";
    result = connection.cursor()
    result.execute("INSERT INTO tbl_notifications(notifMessage,notifText,notifDateTime) VALUES (%s,%s,%s)",(notifMessage,notifText,dateTime))
    result.lastrowid
    result.execute('SELECT last_insert_id()')
    lastID = result.fetchone()
    connection.commit()
    result.close()
    
    subprocess.call(['python', '/var/www/html/scripts/sendMessageToAll.py', str(notifMessage),])
    
    result = connection.cursor()
    result.execute("SELECT userID FROM tbl_users");
    myresult = result.fetchall()
    for userID in myresult:
        saveNotif = connection.cursor()
        saveNotif.execute("INSERT INTO tbl_notification_status(notifUserID,notifID,notifStatus) VALUES (%s,%s,%s)",(userID[0],lastID[0],0))
        connection.commit()
        saveNotif.close()
    result.close()
    
    
def checkReadings(wattage,applianceID):
    connection = mysql.connector.connect(
  host="localhost",
  user="abaynfriends",
  passwd="abaynfriends",
  database="homeautomation"
)
    global lCounter;
    global hCounter;
    
    result = connection.cursor()
    result.execute("SELECT applianceStatus,applianceName,applianceUCL,applianceLCL,applianceRating FROM tbl_appliances WHERE applianceID = %s",(applianceID,))
    for x in result:
        applianceStatus = x[0];
        applianceName = x[1];
        applianceUCL = x[2];
        applianceLCL = x[3];
        applianceRating = x[4];
    result.close()
    dateTime = datetime.datetime.now();
    if(applianceName != None and applianceRating != None):
        if(float(wattage) < applianceLCL and applianceStatus == 1):
            lCounter[applianceID-1] += 1;
            if(lCounter[applianceID-1] == 5):
                if(lCounter[applianceID-1] == 5000):
                    lCounter[applianceID-1] = 6;
                saveLowNotification(wattage,applianceID,applianceName)
        elif(float(wattage) > applianceLCL and applianceStatus == 1):
            hCounter[applianceID-1] += 1;
            if(hCounter[applianceID-1] == 5):
                if(hCounter[applianceID-1] == 5000):
                    hCounter[applianceID-1] = 6;
                saveHighNotification(wattage,applianceID,applianceName)
        else:
            lCounter = [0,0,0,0];
            hCounter = [0,0,0,0];
             
rawVoltage0 = [0]*samples
rawVoltage1 = [0]*samples
rawVoltage2 = [0]*samples
rawVoltage3 = [0]*samples

rawCurrent0 = [0]*samples
rawCurrent1 = [0]*samples
rawCurrent2 = [0]*samples
rawCurrent3 = [0]*samples

while True:
    maxValue0=0;
    minValue0=100000;
    maxValue1=0;
    minValue1=100000;
    maxValue2=0;
    minValue2=100000;
    maxValue3=0;
    minValue3=100000;
    for i in range(samples):
        rawVoltage0[i] = adc.read_adc(0, gain=GAIN)
        rawVoltage1[i] = adc.read_adc(1, gain=GAIN)            
        rawCurrent0[i] = adc.read_adc(2, gain=GAIN)
        rawCurrent1[i] = adc.read_adc(3, gain=GAIN)
        
        rawCurrent2[i] = adc1.read_adc(0, gain=GAIN)
        rawCurrent3[i] = adc1.read_adc(1, gain=GAIN)            
        rawVoltage2[i] = adc1.read_adc(2, gain=GAIN)
        rawVoltage3[i] = adc1.read_adc(3, gain=GAIN)


    A0 = getCurrent(max(rawCurrent0))
    A1 = getCurrent(max(rawCurrent1))
    A2 = getCurrent(max(rawCurrent2))
    A3 = getCurrent(max(rawCurrent3))
    
    V0 = getVoltage(sum(rawVoltage0))
    V1 = getVoltage(sum(rawVoltage1))
    V2 = getVoltage(sum(rawVoltage2))
    V3 = getVoltage(sum(rawVoltage3))
    
    dateLimit = datetime.datetime.now()-datetime.timedelta(minutes=10);
    dateTime = datetime.datetime.now();
    dateLimit = dateLimit.replace(microsecond=0);
    
    saveReadings(A0,V0,2);
    saveReadings(A1,V1,1);
    
    saveReadings(A2,V2,3);
    saveReadings(A3,V3,4);
    
        
    checkReadings(A0*V0,2);
    checkReadings(A1*V1,1);
    
    checkReadings(A2*V2,3);
    checkReadings(A3*V3,4);
    
    time.sleep(1)