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
            subprocess.call(['python', '/var/www/html/scripts/cMessage.py'])
    else:
        #disconnected message
        if dcMessageSent == False:
            #code for sending message here
            cMessageSent = False;
            dcMessageSent = True;
            print("discconnected");
            subprocess.call(['python', '/var/www/html/scripts/dcMessage.py'])
    time.sleep(5)