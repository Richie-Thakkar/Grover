from selenium import webdriver
from selenium.webdriver.chrome.service import Service
from webdriver_manager.chrome import ChromeDriverManager
import time
import requests
import mysql.connector
import json
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
links = driver.find_elements("css selector", 'a')

print("Testing links...")
start = time.time()

working_links = 0
bad_links = 0
bad_links_list = []
for link in links:
    r = requests.head(link.get_attribute('href'))
    if r.status_code == 200 or 999:
        working_links += 1
    else:
        bad_links += 1
        bad_links_list.append((link.get_attribute('href'),r.status_code))

print ("Working links " , working_links)
print ("Bad links ",bad_links)
print(bad_links_list)
mycursor.execute("select * from tests")
if(mycursor.rowcount>0):
    sq="update tests set Working_Count=%s,Broken_Count=%s,Broken_desc=%s where Testname=%s"
    v=(json.dumps(working_links),json.dumps(bad_links),json.dumps(bad_links_list),"Home_Page_Links")
    mycursor.execute(sq,v)
    mydb.commit()
else:
    sq="insert into tests(Testname,Working_Count,Broken_Count,Broken_desc)values(%s,%s,%s,%s)"
    v=("Home_Page_Links",json.dumps(working_links),json.dumps(bad_links),json.dumps(bad_links_list))
    mycursor.execute(sq,v)
    mydb.commit()