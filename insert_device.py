import mariadb
import requests
import json

mydb = mariadb.connect(
  host="192.168.1.20",
  user="test",
  password="itech2022",
  port=3306,
  database="dht"
)

mycursor = mydb.cursor()


api_key = "88dd130dd33aeb1cdbd3547892539927"

# TODO: plz, kd_id
kd_id = "6"
plz = "21107"

url = "http://api.openweathermap.org/geo/1.0/zip?zip=%s,DE&appid=%s" % (plz, api_key)

response = requests.get(url)
data = json.loads(response.text)

lat = data["lat"]
lon = data["lon"]
#print(lat, lon)



# UPDATE device ID?
sql = "INSERT INTO device (kd_id, plz, lat, lon) VALUES (%s, %s, %s, %s)"
val = (kd_id, plz, lat, lon)
mycursor.execute(sql, val)

mydb.commit()

print(mycursor.rowcount, "record inserted.")

# TODO: 

# mycursor.execute("SELECT device_id FROM device WHERE kd_id = %s" % (kd_id))

# result = mycursor.fetchone()

# device_id = (result[0])

# print(device_id)


# mycursor.execute("INSERT INTO temp_out (device_id) VALUES (%s)" % (device_id))

# mydb.commit()

# print(mycursor.rowcount, "record inserted.")