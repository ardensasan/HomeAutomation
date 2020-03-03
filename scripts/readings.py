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
dateNow = datetime.datetime.now()
currentYear = dateNow.year;
currentMonth = dateNow.month;

connection = mysql.connector.connect(host="localhost", user="abaynfriends", passwd="abaynfriends", database="homeautomation")
insertConsumption = connection.cursor()
insertConsumption.execute("INSERT INTO tbl_totalconsumption(totalConsPort,totalConsWatt,totalConsStart,totalConsEnd) "
                          "VALUES (1,0,%s,%s), (2,0,%s,%s), (3,0,%s,%s), (4,0,%s,%s)",(dateNow,dateNow,dateNow,dateNow,dateNow,dateNow,dateNow,dateNow))
connection.commit()
insertConsumption.close()


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
    dateLimit = datetime.datetime.now()-datetime.timedelta(minutes=10);
    dateTime = datetime.datetime.now();
    dateLimit = dateLimit.replace(microsecond=0);
    
    saveRead = connection.cursor()
    saveRead.execute("INSERT INTO tbl_readings(applianceID,rCurrent,rVoltage,rDateTime) VALUES (%s,%s,%s,%s)",(applianceID,current,voltage,dateTime))
    connection.commit()
    saveRead.close()
    
    saveRead = connection.cursor()
    query = 'DELETE FROM tbl_readings WHERE rDateTime < "'+str(dateLimit)+'"';
    saveRead.execute(query);
    connection.commit()
    saveRead.close()    
    
def saveLowNotification(wattage,applianceID,applianceName):
    dateTime = datetime.datetime.now();
    setAbnormal = connection.cursor()
    setAbnormal.execute("UPDATE tbl_appliances SET applianceStatus = 0 WHERE applianceID = %s",(applianceID,))
    connection.commit()
    setAbnormal.close()
    
    notifMessage = "Abnormal low watt readings on Port "+str(applianceID)+" "+str(applianceName);
    notifText = str(wattage)+" W lower than acceptable levels";
    saveLow = connection.cursor()
    saveLow.execute("INSERT INTO tbl_notifications(notifMessage,notifText,notifDateTime) VALUES (%s,%s,%s)",(notifMessage,notifText,dateTime))
    saveLow.lastrowid
    saveLow.execute('SELECT last_insert_id()')
    lastID = saveLow.fetchone()
    connection.commit()
    saveLow.close()
    
    saveUnsentNotif = connection.cursor()
    saveUnsentNotif.execute("INSERT INTO tbl_unsentNotif(notifID,notifMessage) VALUES (%s,%s)",(lastID[0],str(notifMessage)))
    connection.commit()
    saveUnsentNotif.close()
    
    saveLow = connection.cursor()
    saveLow.execute("SELECT userID FROM tbl_users");
    myresult = saveLow.fetchall()
    saveLow.close()
    for userID in myresult:
        saveNotif = connection.cursor()
        saveNotif.execute("INSERT INTO tbl_notification_status(notifUserID,notifID,notifStatus) VALUES (%s,%s,%s)",(userID[0],lastID[0],0))
        connection.commit()
        saveNotif.close()
    
def saveHighNotification(wattage,applianceID,applianceName):
    dateTime = datetime.datetime.now();
    setAbnormal = connection.cursor()
    setAbnormal.execute("UPDATE tbl_appliances SET applianceStatus = 0 WHERE applianceID = %s",(applianceID,))
    connection.commit()
    setAbnormal.close()
    
    notifMessage = "Abnormal high watt readings on Port "+str(applianceID)+" "+str(applianceName);
    notifText = str(wattage)+" W lower than acceptable levels";
    saveHigh = connection.cursor()
    saveHigh.execute("INSERT INTO tbl_notifications(notifMessage,notifText,notifDateTime) VALUES (%s,%s,%s)",(notifMessage,notifText,dateTime))
    saveHigh.lastrowid
    saveHigh.execute('SELECT last_insert_id()')
    lastID = saveHigh.fetchone()
    connection.commit()
    saveHigh.close()
    
    saveUnsentNotif = connection.cursor()
    saveUnsentNotif.execute("INSERT INTO tbl_unsentNotif(notifID,notifMessage) VALUES (%s,%s)",(lastID[0],str(notifMessage)))
    connection.commit()
    saveUnsentNotif.close()
    
    
    saveHigh = connection.cursor()
    saveHigh.execute("SELECT userID FROM tbl_users");
    myresult = saveHigh.fetchall()
    saveHigh.close()
    for userID in myresult:
        saveNotif = connection.cursor()
        saveNotif.execute("INSERT INTO tbl_notification_status(notifUserID,notifID,notifStatus) VALUES (%s,%s,%s)",(userID[0],lastID[0],0))
        connection.commit()
        saveNotif.close()
    
    
def checkReadings(wattage,applianceID):
    global lCounter;
    global hCounter;
    
    
    checkRead = connection.cursor()
    checkRead.execute("SELECT applianceStatus,applianceName,applianceUCL,applianceLCL,applianceRating FROM tbl_appliances WHERE applianceID = %s",(applianceID,))
    readResult = checkRead.fetchall()
    checkRead.close()
    for readR in readResult:
        applianceStatus = readR[0];
        applianceName = readR[1];
        applianceUCL = readR[2];
        applianceLCL = readR[3];
        applianceRating = readR[4];
        if(applianceName != None and applianceRating != None and applianceUCL != None and applianceLCL != None):
            if(float(wattage) < applianceLCL and applianceStatus == 1):
                lCounter[applianceID-1] += 1;
                if(lCounter[applianceID-1] == 2):
                    if(lCounter[applianceID-1] == 5000):
                        lCounter[applianceID-1] = 3;
                    saveLowNotification(wattage,applianceID,applianceName)
            elif(float(wattage) > applianceUCL and applianceStatus == 1):
                hCounter[applianceID-1] += 1;
                if(hCounter[applianceID-1] == 2):
                    if(hCounter[applianceID-1] == 5000):
                        hCounter[applianceID-1] = 3;
                    saveHighNotification(wattage,applianceID,applianceName)
            else:
                setAbnormal = connection.cursor()
                setAbnormal.execute("UPDATE tbl_appliances SET applianceReadingStatus = 0 WHERE applianceID = %s",(applianceID,))
                connection.commit()
                setAbnormal.close()
                lCounter[applianceID-1] = 0;
                hCounter[applianceID-1] = 0;

def updateTotalWatts(wattage,applianceID):
    dateNow = datetime.datetime.now();
    getMaxID = connection.cursor()
    getMaxID.execute("SELECT MAX(totalConsID) FROM tbl_totalconsumption WHERE totalConsPort = %s", (applianceID,))
    totalConsID = getMaxID.fetchone()
    getMaxID.close()

    setAverageWatt = connection.cursor()
    setAverageWatt.execute("UPDATE tbl_totalconsumption SET totalConsWatt = ((totalConsWatt+ %s)/2),totalConsEnd = %s WHERE totalConsID = %s", (wattage,str(dateNow),totalConsID[0]))
    connection.commit()
    setAverageWatt.close()

rawVoltage0 = [0]*samples
rawVoltage1 = [0]*samples
rawVoltage2 = [0]*samples
rawVoltage3 = [0]*samples

rawCurrent0 = [0]*samples
rawCurrent1 = [0]*samples
rawCurrent2 = [0]*samples
rawCurrent3 = [0]*samples

while True:
    connection = mysql.connector.connect(host="localhost", user="abaynfriends", passwd="abaynfriends", database="homeautomation")
    dateNow = datetime.datetime.now();
    if dateNow.month != currentMonth:
        currentMonth = dateNow.month
        currentYear = dateNow.year
        insertConsumption = connection.cursor()
        insertConsumption.execute(
            "INSERT INTO tbl_totalconsumption(totalConsPort,totalConsWatt,totalConsStart,totalConsEnd) "
            "VALUES (1,0,%s,%s), (2,0,%s,%s), (3,0,%s,%s), (4,0,%s,%s)",
            (dateNow, dateNow, dateNow, dateNow, dateNow, dateNow, dateNow, dateNow))
        connection.commit()
        insertConsumption.close()
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

    saveReadings(A0,V0,2);
    saveReadings(A1,V1,1);
    
    saveReadings(A2,V2,3);
    saveReadings(A3,V3,4);

    watt0 = A0*V0;
    watt1 = A1*V1;
    watt2 = A2*V2;
    watt3 = A3*V3;

    #update watt total consumptiom
    updateTotalWatts(watt0, 2);
    updateTotalWatts(watt1, 1);

    updateTotalWatts(watt2, 3);
    updateTotalWatts(watt3, 4);

    #check for abnormal readings

    checkReadings(watt0,2);
    checkReadings(watt1,1);

    checkReadings(watt2,3);
    checkReadings(watt3,4);
    
    time.sleep(1)