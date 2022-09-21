import mariadb

mydb = mariadb.connect(
  host="192.168.1.20",
  user="test",
  password="itech2022",
  port=3306,
  database="dht"
)

mycursor = mydb.cursor()

sql = "INSERT INTO kunde (vorname, nachname, email) VALUES (%s, %s, %s)"
val = [
  ('Max', 'Mustermann', 'max.mustermann@gmail.com'),
  ('Anne', 'Miregal', 'anneee@web.de'),
  ('Junge', 'Wayne', 'digga@hotmail.de'),
]
mycursor.executemany(sql, val)

mydb.commit()

print(mycursor.rowcount, "was inserted.")