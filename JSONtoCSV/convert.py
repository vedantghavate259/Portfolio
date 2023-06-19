import json
import csv
import pandas as pd
from collections import defaultdict


collistl=[]
collist=[]
def checkprint(ft,collist2,s):
    if isinstance(ft,dict)==True:
        i=str(collist2[-1])
        for k in ft:
            if(isinstance(k,(dict,list))==True):
                i=str(collist2[-1])
                rt=s+str(k)+i
                collist2.append(rt)
            else:
                collist2.append(k+s)
            collist2=checkprint(ft[k],collist2,'')
    elif isinstance(ft,list)==True:
        #print(ft)
        m=str(collist2[-1])
        for r in range(1,len(ft)+1):
            y=m+str(r)
            if(isinstance(ft[r-1],(dict,list))!=True):
                y=s+'_'+y
                collist2.append(y)
                collist2=checkprint(ft[r-1],collist2,y)
            elif(isinstance(ft[r-1],(dict))==True):
                y=s+'_'+y
                #collist2.append(y)
                collist2=checkprint(ft[r-1],collist2,y)
                #collist2=checkprint(ft[r-1],collist2)
            else:
                y=''
                m=''
                collist2=checkprint(ft[r-1],collist2,'')
    else:
        #print(ft)
        #ts="\""+str(ft)+"\""
        #collist2.append(ts)
        pass
    return collist2
 
 
 
def checkprint2(ft,collist2,s):
    if isinstance(ft,dict)==True:
        i=str(collist2[-1])
        for k in ft:
            if(isinstance(k,(dict,list))==True):
                i=str(collist2[-1])
                rt=s+str(k)+i
                collist2.append(rt)
            else:
                collist2.append(k+s)
            collist2=checkprint2(ft[k],collist2,'')
    elif isinstance(ft,list)==True:
        #print(ft)
        m=str(collist2[-1])
        for r in range(1,len(ft)+1):
            y=m+str(r)
            if(isinstance(ft[r-1],(dict,list))!=True):
                y=s+'_'+y
                collist2.append(y)
                collist2=checkprint2(ft[r-1],collist2,y)
            elif(isinstance(ft[r-1],(dict))==True):
                y=s+'_'+y
                #collist2.append(y)
                collist2=checkprint2(ft[r-1],collist2,y)
                #collist2=checkprint(ft[r-1],collist2)
            else:
                y=''
                m=''
                collist2=checkprint2(ft[r-1],collist2,'')
    else:
        #print(ft)
        #ts="\""+str(ft)+"\""
        ts=str(ft)
        collist2.append(ts)
        pass
    return collist2
 
 
with open('C:/Users/nicky/Desktop/TA/JSONtoCSV/20K documents_sample.json') as json_file:
    jsondata = json.load(json_file)
    for key,value in jsondata.items():
        for y in value:
            collistm=[""]
            collistl.append(checkprint(y,collistm,''))
            
with open('C:/Users/nicky/Desktop/TA/JSONtoCSV/20K documents_sample.json') as json_file:
    jsondata = json.load(json_file)
    for key,value in jsondata.items():
        for y in value:
            #print(y)
            collistm=[""]
            collist.append(checkprint2(y,collistm,''))
            
print("no of papers ",len(collist))

#for i in collist:
    #print(i)


temp=[]

for i in collistl:
    #print(i)
    if collistl[0]==i:
        temp=i
    else:
        mark=0
        for m in range(len(i)):
            if i[m] not in temp:
                #print(i[m])
                mr=i[mark]
                prev=temp.index(mr)
                temp.insert(prev+1,i[m])
            else:
                mark=m
#print(temp)
                        
          
finalarr=[]      
print("list length",len(i))
print("list length",len(temp))
count=0
i=collist
jm=0
for i in collist:
    final=['']*895
    for val in i:
        if(val not in temp):
            final[jm]=val
        else:
            jm=temp.index(val)
            final[jm]=''
    finalarr.append(final)
    
#print("***************************************************************************")
#print(finalarr)        
                
                


with open('Result.csv', 'w+', encoding='utf-8') as f:
    writer = csv.writer(f)
    writer.writerow(temp)       
    writer.writerows(finalarr)