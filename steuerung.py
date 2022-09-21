# abspeichern der AUßENTEMPERATUR in der datenbank mithilfe der owm api
import Adafruit_DHT
import RPi.GPIO as GPIO
import requests
import json
import mariadb
import time
from datetime import datetime

# Variablen Setup
DHT_SENSOR = Adafruit_DHT.DHT11
DHT_PIN = 23
in1 = 4 #17
in2 = 17
in3 = 27
in4 = 22

# careful lowering this, at some point you run into the mechanical limitation of how quick your motor can move
step_sleep = 0.002
step_count = 409 # 5.625*(1/64) per step, 4096 steps is 360°
step_position = 0 #Todo :immer Position der Heizung beachten-> DB schreiben?
direction = True # False for clockwise, True for counter-clockwise
dht_light = 0 #Mock Liichtsensor
# defining stepper motor sequence (found in documentation http://www.4tronix.co.uk/arduino/Stepper-Motors.php)
step_sequence = [[1,0,0,1],
                 [1,0,0,0],
                 [1,1,0,0],
                 [0,1,0,0],
                 [0,1,1,0],
                 [0,0,1,0],
                 [0,0,1,1],
                 [0,0,0,1]]



motor_pins = [in1,in2,in3,in4]
motor_step_counter = 0 ;

def cleanup():
    GPIO.output( in1, GPIO.LOW )
    GPIO.output( in2, GPIO.LOW )
    GPIO.output( in3, GPIO.LOW )
    GPIO.output( in4, GPIO.LOW )
    GPIO.cleanup()
 

while True:
# setting up
  GPIO.setmode( GPIO.BCM )
  GPIO.setup( in1, GPIO.OUT )
  GPIO.setup( in2, GPIO.OUT )
  GPIO.setup( in3, GPIO.OUT )
  GPIO.setup( in4, GPIO.OUT )

    # initializing
  GPIO.output( in1, GPIO.LOW )
  GPIO.output( in2, GPIO.LOW )
  GPIO.output( in3, GPIO.LOW )
  GPIO.output( in4, GPIO.LOW )
# TODO: DEVICE ID
  dev_id = "5"

  mydb = mariadb.connect(
  host="192.168.1.20",
  user="test",
  password="itech2022",
  port=3306,
  database="dht"
  )

  mycursor = mydb.cursor()

  # aktuelles datum
  dt = datetime.now()
  time_now = dt.strftime("%Y-%m-%d %H:%M:%S")

  # abfrage der letzten temperaturmessung aus der db
  mycursor.execute("SELECT time FROM temp_out WHERE device_id = %s" % (dev_id))

  result = mycursor.fetchone()

  # in string
  time_last_rec = (result[0])
  time_last_rec_string = time_last_rec.strftime("%Y-%m-%d %H:%M:%S")

  # in time object
  fmt = '%Y-%m-%d %H:%M:%S'
  tstamp1 = datetime.strptime(time_now, fmt)
  tstamp2 = datetime.strptime(time_last_rec_string, fmt)

  # differenz berechnen
  td = tstamp1 - tstamp2
  td_mins = int(round(td.total_seconds() / 60))

  # api nur anfragen, wenn letzte abfrage mindestens x min alt ist
  if td_mins >=0:

    # abfrage der längen- und breitengrade um die temperatur bestimmen zu können
    mycursor.execute("SELECT lat, lon FROM device WHERE device_id = %s" % (dev_id))

    result = mycursor.fetchone()

    lat = float(result[0])
    lon = float(result[1])
    print("latitude: ", lat, "longitude:", lon)

    # api call
    api_key = "6f263d260609433b363fb5b299a84a86"
    url = "https://api.openweathermap.org/data/2.5/onecall?lat=%s&lon=%s&appid=%s&units=metric" % (lat, lon, api_key)

    response = requests.get(url)
    data = json.loads(response.text)
    
    # temperatur auslesen
    temp = data["current"]["temp"]
    print(temp, "°C")
    

   #innentemperatur auslesen und in DB schreiben
    #TODO Datenformat temperature anpassen
    humidity, temperature = Adafruit_DHT.read_retry(DHT_SENSOR, DHT_PIN)
    mycursor.execute("INSERT INTO temp_in (device_id,temp) VALUES (%s,%s) "% (dev_id,temperature))
    #mycursor.execute("INSERT INTO temp_in (device_id,temp) VALUES (%s,%s) "% (dev_id,8))

    # temperatur in die datenbank schreiben
    mycursor.execute("UPDATE temp_out SET temp = '%s', time = '%s' WHERE device_id = '%s'" % (temp, time_now, dev_id))

    mydb.commit()

    print('Last updated %s minutes ago' % td_mins)

  # wenn letzte abfrage noch nicht 60 min alt ist
  else:
    td_remaining = 60 - td_mins
    print('Please try again in %s minutes' % td_remaining)

  print(mycursor.rowcount, "record(s) affected")


#TODO Temperatur einstellung vom Webseite ziehen /offset und On/OF beachten
#Wohnung darf nicht zu kalt werden -> Heizung an
  if temperature<10:
# if 8<10:
        try:
            i = 0
            direction = False
            for i in range(step_count):
                for pin in range(0, len(motor_pins)):
                    GPIO.output( motor_pins[pin], step_sequence[motor_step_counter][pin] )
                motor_step_counter = (motor_step_counter + 1) % 8
                time.sleep( step_sleep )
            print("Temperatur war zu niedrig")
        except KeyboardInterrupt:
            cleanup()
            exit( 1 )
#Licht und zu niedrige Temperatur -> Heizung an 
  elif temperature<18 and dht_light > 4: 
        try:
            i = 0
            direction = False
            for i in range(step_count):
                for pin in range(0, len(motor_pins)):
                    GPIO.output( motor_pins[pin], step_sequence[motor_step_counter][pin] )
                motor_step_counter = (motor_step_counter + 1) % 8
                time.sleep( step_sleep )
            print("Temperatur war zu niedrig!")
        except KeyboardInterrupt:
            cleanup()
            exit( 1 )
#Kein Licht , aber warm -> Heizung ausmachen
  elif temperature>20 and dht_light < 4:      
        try:
            i = 0
            direction = True
            for i in range(step_count):
                for pin in range(0, len(motor_pins)):
                    GPIO.output( motor_pins[pin], step_sequence[motor_step_counter][pin] )
                motor_step_counter = (motor_step_counter - 1) % 8
                time.sleep( step_sleep )
            print("Temperatur war zu hoch")
        except KeyboardInterrupt:
            cleanup()
            exit( 1 )
#Licht egal , aber sehr warm -> Heizung ausmachen
  elif temperature>22:      
        try:
            i = 0
            direction = True
            for i in range(step_count):
                for pin in range(0, len(motor_pins)):
                    GPIO.output( motor_pins[pin], step_sequence[motor_step_counter][pin] )
                motor_step_counter = (motor_step_counter - 1) % 8
                time.sleep( step_sleep )
            print("Temperatur war zu hoch!")
        except KeyboardInterrupt:
            cleanup()
            exit( 1 )
 


  cleanup()
  time.sleep(20)
 
  