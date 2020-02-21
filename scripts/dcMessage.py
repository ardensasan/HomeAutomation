import serial
import RPi.GPIO as GPIO      
import os, time
 
GPIO.setmode(GPIO.BOARD)   

dcMessage = serial.Serial("/dev/ttyAMA0", baudrate=9600, timeout=1)
 
# Transmitting AT Commands to the Modem
# '\r\n' indicates the Enter key
 
dcMessage.write('AT'+'\r\n')
dcMessage.read(5)


dcMessage.write('ATE0'+'\r\n')      # Disable the Echo
dcMessage.read(5)


dcMessage.write('AT+CMGF=1'+'\r\n')  # Select Message format as Text mode 
dcMessage.read(5)


# Sending a message to a particular Number
 
dcMessage.write('AT+CMGS="09973386295"'+'\r\n')
dcMessage.read(5)


dcMessage.write('RPi is disconnected'+'\r\n')  # Message
dcMessage.read(5)

 
dcMessage.write("\x1A") # Enable to send SMS
dcMessage.close()