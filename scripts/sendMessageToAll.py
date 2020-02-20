import serial
import RPi.GPIO as GPIO      
import os, time
import sys
import mysql.connector

connection = mysql.connector.connect(
  host="localhost",
  user="abaynfriends",
  passwd="abaynfriends",
  database="homeautomation"
)
    
message = str(sys.argv[1]);

# Enable Serial Communication
counter = 0;
getUser = connection.cursor()
getUser.execute("SELECT userPhoneNumber FROM tbl_users")
for userDetails in getUser:
    phoneNumber = "0"+str(userDetails[0]);
    port1 = serial.Serial("/dev/ttyAMA0", baudrate=9600, timeout=1)
    port1.write('AT'+'\r\n')
    rcv1 = port1.read(5)
    print rcv1
     
    port1.write('ATE0'+'\r\n')      # Disable the Echo
    rcv1 = port1.read(5)
    print rcv1
     
    port1.write('AT+CMGF=1'+'\r\n')  # Select Message format as Text mode 
    rcv1 = port1.read(5)
    print rcv1
     
    port1.write('AT+CNMI=2,1,0,0,0'+'\r\n')   # New SMS Message Indications
    rcv1 = port1.read(5)
    print rcv1
     
    # Sending a message to a particular Number
     
    port1.write('AT+CMGS="'+phoneNumber+'"'+'\r\n')
    rcv1 = port1.read(5)
    print rcv1
     
    port1.write(message+'\r\n')  # Message
    rcv1 = port1.read(5)
    print rcv1
     
    port1.write("\x1A") # Enable to send SMS
    port1.close()
    
    print(phoneNumber)
    time.sleep(1)
getUser.close()


