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
phoneNumber = str(sys.argv[2]);

# Enable Serial Communication
port = serial.Serial("/dev/ttyAMA0", baudrate=9600, timeout=1)
# Transmitting AT Commands to the Modem
# '\r\n' indicates the Enter key
 
port.write('AT'+'\r\n')
rcv = port.read(5)
print rcv
time.sleep(1)

port.write('ATE0'+'\r\n')      # Disable the Echo
rcv = port.read(5)
print rcv
time.sleep(1)

port.write('AT+CMGF=1'+'\r\n')  # Select Message format as Text mode 
rcv = port.read(5)
print rcv
time.sleep(1)

port.write('AT+CNMI=2,1,0,0,0'+'\r\n')   # New SMS Message Indications
rcv = port.read(5)
print rcv
time.sleep(1)
 
# Sending a message to a particular Number
 
port.write('AT+CMGS="'+phoneNumber+'"'+'\r\n')
rcv = port.read(5)
print rcv
time.sleep(1)

port.write(message+'\r\n')  # Message
rcv = port.read(5)
print rcv
time.sleep(1)

port.write("\x1A") # Enable to send SMS
time.sleep(1)
port.close()

