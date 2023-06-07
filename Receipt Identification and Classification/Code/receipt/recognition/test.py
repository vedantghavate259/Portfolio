import numpy as np
from keras.models import load_model
from pymongo import MongoClient 
import csv

try: 
	conn = MongoClient() 
	print("Connected successfully!!!") 
except: 
	print("Could not connect to MongoDB") 

# database 
db = conn.Receipt_db

# Created or Switched to collection names: my_gfg_collection 
categorization = db.categorization
items=db.items
temp=db.temp
file = open("C:/xampp3/htdocs/receipt/recognition/bi_grams5.txt", 'rt')
list = [x.strip('\n') for x in file.readlines()]

bigrams_indices = dict((bi, i) for i, bi in enumerate(list))
indices_bigrams = dict((i, bi) for i, bi in enumerate(list))

def encode(str):
    str = '@'+str+'@'
    zipped = zip(*[str[i:] for i in range(2)])
    pairs=[x for x in zipped]
    result = np.zeros((50),dtype=np.int64)
    for i,pair in enumerate(pairs):
        result[i] = bigrams_indices[pair[0]+pair[1]]
    return result
model = load_model('trained_model')
# result=model.predict(encode('HOT CHOCOLATE')[np.newaxis])
# result1=result.flatten()

# value=np.argmax(result1,axis=0)
# val=int(value)+1
# print(val)
    
#fetching from mongo trial code


amt1=0
amt2=0
amt3=0
amt4=0
for x in items.find():
    name=x['itemname']
    amount=int(float(x['price']))

    result=model.predict(encode(name)[np.newaxis])
    result1=result.flatten()

    value=np.argmax(result1,axis=0)
    val=int(value)+1
    print(val)
    if(val==1):
        amt1+=amount
    elif(val==2):
        amt2+=amount
    elif (val==3):
        amt3+=amount
    else:
        amt4+=amount
    itemtemp = { 
	"category":val,
    "name":name
	}
    temp.insert_one(itemtemp) 
iamt1=int(amt1)
iamt2=int(amt2)
iamt3=int(amt3)
iamt4=int(amt4)
item1 = { 
	"category":"Food and Restaurant",
    "price":iamt1
	} 
item2 = { 
	"category":"Entertainment",
    "price":iamt2
	}
item3 = { 
	"category":"Travel",
    "price":iamt3
	}
item4 = { 
	"category":"Miscellaneous",
    "price":iamt4
	}

item1 = categorization.insert_one(item1)
item2 = categorization.insert_one(item2)
item3 = categorization.insert_one(item3)
item4 = categorization.insert_one(item4)

print(item1)
print(item2)
print(item3)
print(item4)

#end off trial code
