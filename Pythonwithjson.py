import re
import string
import json

sayi = {}
metin = open('test.txt', 'r')
metin_string = metin.read().lower()
eslesme = re.findall(metin_string)

for kelime in eslesme:
counter = sayi.get(kelime, 0)
sayi[kelime] = counter + 1

sayi_list = sayi.keys()


for kelimeler in sayi_list:
f = open('test.json', 'w')
json.dump(sayi, f)
f.close()
