from selenium import webdriver
from selenium.webdriver.chrome.service import Service
from webdriver_manager.chrome import ChromeDriverManager
import time
import requests
import urllib
import json
import mysql.connector

mydb = mysql.connector.connect(
  host="localhost",
  user="root",
  password="Inquistivepizza@55",
  database="shop_db"
)
mycursor=mydb.cursor(buffered=True)

options = webdriver.ChromeOptions()
options.add_argument("start-maximized")
options.add_argument('disable-infobars')
driver = webdriver.Chrome(service=Service(ChromeDriverManager().install()))
driver.get("http://localhost/gs")
driver.find_element("id",'email').send_keys('aaa@gma.com')
driver.find_element("id",'password').send_keys('12345')
driver.find_element("id",'b').click()
driver.find_element("id",'shop').click()
links = driver.find_elements("css selector", 'img')

print("Testing Images...")
start = time.time()

proper_images = 0
bad_images = 0
bad_images_list = []
for link in links:
    r = requests.head(link.get_attribute('src'))
    if r.status_code == 200:
        proper_images += 1
    else:
        bad_images += 1
        bad_images_list.append((link.get_attribute('src'),r.status_code))
    
print ("Working Images " , proper_images)
print ("Bad Images ",bad_images)
print(bad_images_list)
mycursor.execute("select * from tests")
if(mycursor.rowcount>0):
    sq="update tests set Working_Count=%s,Broken_Count=%s,Broken_desc=%s where Testname=%s"
    v=(json.dumps(proper_images),json.dumps(bad_images),json.dumps(bad_images_list),"Shop_Page_Images")
    mycursor.execute(sq,v)
    mydb.commit()
else:
    sq="insert into tests(Testname,Working_Count,Broken_Count,Broken_desc)values(%s,%s,%s,%s)"
    v=("Shop_Page_Images",json.dumps(proper_images),json.dumps(bad_images),json.dumps(bad_images_list))
    mycursor.execute(sq,v)
    mydb.commit()