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
sendMessageToAll = connection.cursor()
sendMessageToAll.execute("SELECT userPhoneNumber FROM tbl_users")
sendMessageUser = sendMessageToAll.fetchall()
sendMessageToAll.close()

for userDetails in sendMessageUser:
    phoneNumber = "0"+str(userDetails[0]);
    sendMessageToAll = serial.Serial("/dev/ttyAMA0", baudrate=9600, timeout=1)
    sendMessageToAll.write('AT'+'\r\n')
    rcv1 = sendMessageToAll.read(5)
    print rcv1
     
    sendMessageToAll.write('ATE0'+'\r\n')      # Disable the Echo
    rcv1 = sendMessageToAll.read(5)
    print rcv1
     
    sendMessageToAll.write('AT+CMGF=1'+'\r\n')  # Select Message format as Text mode 
    rcv1 = sendMessageToAll.read(5)
    print rcv1
     
    sendMessageToAll.write('AT+CNMI=2,1,0,0,0'+'\r\n')   # New SMS Message Indications
    rcv1 = sendMessageToAll.read(5)
    print rcv1
     
    # Sending a message to a particular Number
     
    sendMessageToAll.write('AT+CMGS="'+phoneNumber+'"'+'\r\n')
    rcv1 = sendMessageToAll.read(5)
    print rcv1
     
    sendMessageToAll.write(message+'\r\n')  # Message
    rcv1 = sendMessageToAll.read(5)
    print rcv1
     
    sendMessageToAll.write("\x1A") # Enable to send SMS
    sendMessageToAll.close()
    
    print(phoneNumber)
    time.sleep(1)


