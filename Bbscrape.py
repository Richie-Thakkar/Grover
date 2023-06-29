import mysql.connector

mydb = mysql.connector.connect(
  host="localhost",
  user="root",
  password="Inquistivepizza@55",
  database="shop_db"
)
mycursor=mydb.cursor(buffered=True)
import requests
from bs4 import BeautifulSoup as soup


header = {'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.61 Safari/537.36'}


r = requests.get('https://www.bigbasket.com/custompage/sysgenpd/?type=pc&slug=potato-onion-tomato',headers=header)
bsobj = soup(r.content,"html.parser")


import json



comp = json.loads(r.text)


comp = comp['tab_info']





a = []
for i in comp[0]['product_info']['products']:
    a.append(i)
    




name = []
sp = []
imgurl=[]
link=[]
cnt=0
for j in a:
    name.append(j['p_desc'])
    sp.append(j['sp'])
    imgurl.append(j['p_img_url'])
    link.append(j['absolute_url'])
    cnt=cnt+1
prefixurl="https://www.bigbasket.com"
mycursor.execute("select * from bbdata")
if(mycursor.rowcount>0):
    print ("Records will update")
    for idx in range(0,cnt):
        print(name[idx])
        print (sp[idx])
        link[idx]=prefixurl+link[idx]
        sq="update bbdata set sp=%s,link=%s,imgurl=%s where name=%s"
        v=(sp[idx],link[idx],imgurl[idx],name[idx])
        mycursor.execute(sq,v)
    mydb.commit()
    print(mycursor.rowcount, "record(s) affected")
else:
    for k in range(0,cnt):
        print (sp[k])
        link[k]=prefixurl+link[k]
        sql = "INSERT INTO bbdata (name, sp, imgurl, link) VALUES (%s, %s, %s, %s)"
        val = (name[k], sp[k], imgurl[k], link[k])
        mycursor.execute(sql, val)
        mydb.commit()
        print(mycursor.rowcount, "record(s) affected")
