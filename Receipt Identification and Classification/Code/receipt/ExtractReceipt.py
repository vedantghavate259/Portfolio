import re
from pymongo import MongoClient
import glob
import os
import json
import datefinder
from date_extractor import extract_dates
import datetime
import uuid

client = MongoClient('localhost', 27017)
db = client['Receipt_db']
collection = db['items']

def dict(filename,arr,dt):
    data = {}
    ar = len(arr)
    print(ar)
    if ar == 4:
        data["itemid"] = uuid.uuid1().hex
        data["itemname"] = arr[0]
        st = re.split(r'\s{1,}', arr[1])
        if len(st) > 1:
            qt = st[0]
            data["price"] = float(str(arr[2]).replace("C", "0")) * float(str(qt))
        else :
            data["price"] =  float( str(arr[2]).replace("C", "0")) * float(str(arr[1]).replace("C", "0"))
        data["date"] =  dt
        data["type"] = "inventory"
    
    elif ar == 5:
        data["itemid"] = uuid.uuid1().hex
        data["itemname"] = arr[0]
        st = re.split(r'\s{1,}', arr[1])
        if len(st) > 1:
            qt = st[0]
            data["price"] = float(str(arr[2]).replace("C", "0")) * float(str(qt))
        else :
            data["price"] =  float( str(arr[2]).replace("C", "0")) * float(str(arr[1]).replace("C", "0"))
        data["date"] = dt
        data["type"] = "inventory"
    elif ar == 3:
        data["filename"] = filename
        data["itemname"] = arr[0]
        data["price"] = arr[1]

    elif ar == 1:
        dt1 = arr[0].split('Amount')
        data["itemid"] = uuid.uuid1().hex
        data["vehical"] = dt1[0]
        data["amount"] = dt1[1]
        data["date"] = dt
        data["type"] = "Vehical"

    # print(len(data))
    # print(data)
    # add t0 mongodb
    try:
        json_dataf = json.loads(json.dumps(data))
        collection.update(json_dataf, json_dataf, True)
        print("\n Record added \n")

        if len(json_dataf) > 0 :
            if "vehical" in json_dataf:
                print("Vehical data")
                print(json_dataf)
            else:
                print("inventory")
                print(json_dataf)


    except BaseException:
        print("An exception occurred" + BaseException)


#os.chdir(r'D:\2020Projects\files')
#myFiles = glob.glob('*.txt')

path = 'C:/xamppmain/htdocs/receipt/uploads2/*.txt'   
myFiles = glob.glob(path)  

newdate =""
#print(myFiles)
for fle in myFiles:
    # open the file and then call .read() to get the text
    #print("new file" + fle)
    isnewdate = False

    with open(fle) as f:
        for line in f:
            dt = ""
            # in python 2
            # print line
            # in python 3
            z = re.match(
                "(([\w+ :+)(@%])+\s+((\d+\s+\d+\.\d+|\d+\.\d+)\s+\d+\.\d+)\s+(\d+\.\d+))|([\w+ : -.]+\s+\d+\.\d+)",
                line)

            try:
                # print(line)
                # regEx = r'(?:\d{1,2}[-/th|st|nd|rd\s]*)?(?:Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec)?[a-z\s,.]*(?:\d{1,2}[-/th|st|nd|rd)\s,]*)+(?:\d{2,4})+'
                # result = re.findall(regEx, line)
                dates = extract_dates(line)
                if isnewdate == False:

                    if len(dates) > 0 :
                        newdate = datetime.datetime.strptime(str(dates[0].date()), "%Y-%m-%d").strftime("%d/%m/%Y")
                        if dates[0].year > 2018 :
                            #print(newdate)
                            isnewdate = True

            except BaseException:
                print("An exception occurred")

            if z:
                print(line)
                dt = re.split(r'\s{2,}', line)
                print(dt)
                dict(fle,dt,newdate)



