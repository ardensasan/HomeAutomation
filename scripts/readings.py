import time
import Adafruit_ADS1x15
import math
import mysql.connector
import datetime
connection = mysql.connector.connect(
  host="localhost",
  user="abaynfriends",
  passwd="abaynfriends",
  database="homeautomation"
)
adc = Adafruit_ADS1x15.ADS1115(address=0x49)
adc1 = Adafruit_ADS1x15.ADS1115(address=0x49)
adc.data_rate = 860;
adc1.data_rate = 860;
GAIN = 1
samples = 10;

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
    
    cursor = connection.cursor()
    cursor.execute("INSERT INTO tbl_readings(applianceID,rCurrent,rVoltage,rDateTime) VALUES (%s,%s,%s,%s)",(applianceID,current,voltage,dateTime))
    connection.commit()
    cursor.close()
    
    cursor = connection.cursor()
    query = 'DELETE FROM tbl_readings WHERE rDateTime < "'+str(dateLimit)+'"';
    cursor.execute(query);
    connection.commit()
    cursor.close()
    
    
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
    
    time.sleep(1)