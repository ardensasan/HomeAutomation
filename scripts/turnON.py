#!/usr/bin/env python
import sys
import RPi.GPIO as GPIO
pin = int(sys.argv[1]);
GPIO.setmode(GPIO.BOARD)
GPIO.setup(pin, GPIO.OUT)
GPIO.output(pin, True)