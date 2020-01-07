import serial
import RPi.GPIO as GPIO      
import os, time
 
GPIO.setmode(GPIO.BOARD)   

port = serial.Serial("/dev/ttyAMA0", baudrate=9600, timeout=1)
 
# Transmitting AT Commands to the Modem
# '\r\n' indicates the Enter key
 
port.write('AT'+'\r\n')
port.read(10)


port.write('ATE0'+'\r\n')      # Disable the Echo
port.read(10)


port.write('AT+CMGF=1'+'\r\n')  # Select Message format as Text mode 
port.read(10)


# Sending a message to a particular Number
 
port.write('AT+CMGS="9956139395"'+'\r\n')
port.read(10)


port.write('RPi internet connecion has been restored'+'\r\n')  # Message
port.read(10)

 
port.write("\x1A") # Enable to send SMS