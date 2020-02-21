#!/usr/bin/env python
import urllib.request
import time
import subprocess
cMessageSent = True;
dcMessageSent = False
def connect():
    try:
        urllib.request.urlopen('http://google.com') #Python 3.x
        return True
    except:
        return False
while True:
    if connect():
        #connected message
        if cMessageSent == False:
            #code for sending message here
            cMessageSent = True;
            dcMessageSent = False;
            print("connected");
            notifMessage = "RPi internet connection has been restored";
            subprocess.call(['python', '/var/www/html/scripts/sendMessageToAll.py', str(notifMessage),])
    else:
        #disconnected message
        if dcMessageSent == False:
            #code for sending message here
            cMessageSent = False;
            dcMessageSent = True;
            print("discconnected");
            notifMessage = "RPi is disconnected";
            subprocess.call(['python', '/var/www/html/scripts/sendMessageToAll.py', str(notifMessage),])
    time.sleep(20)