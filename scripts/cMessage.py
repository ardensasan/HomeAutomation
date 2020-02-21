import serial
import RPi.GPIO as GPIO      
import os, time
 
GPIO.setmode(GPIO.BOARD)   

cMessage = serial.Serial("/dev/ttyAMA0", baudrate=9600, timeout=1)
 
# Transmitting AT Commands to the Modem
# '\r\n' indicates the Enter key
 
cMessage.write('AT'+'\r\n')
cMessage.read(5)


cMessage.write('ATE0'+'\r\n')      # Disable the Echo
cMessage.read(5)


cMessage.write('AT+CMGF=1'+'\r\n')  # Select Message format as Text mode 
cMessage.read(5)


# Sending a message to a particular Number
 
cMessage.write('AT+CMGS="9956139395"'+'\r\n')
cMessage.read(5)


cMessage.write('RPi internet connecion has been restored'+'\r\n')  # Message
cMessage.read(5)

 
cMessage.write("\x1A") # Enable to send SMS
cMessage.close()