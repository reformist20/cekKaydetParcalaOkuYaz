#3.parti kütüphaneler
import requests #pip install requests
from prettytable import PrettyTable #pip install PrettyTable
#########################
#Varsayılan kütüphaneler
from collections import Counter, OrderedDict
from xml.dom import minidom
import xml.etree.ElementTree as ET
#########################

#Değişkenler
veri_adresi = "http://www.gutenberg.org/cache/epub/2489/pg2489.txt";
xmldosyasi = "cikti.xml";
xmlden_okunacak_adet = 10;
#########################

#CURL ile veriyi çekme
r=requests.get(veri_adresi, headers={"content-type":"text"})
#########################

#Çekilen veriyi parçalayıp sıralama
kelimeler = r.text.split(' ')
adetle = Counter(kelimeler)
adetle = OrderedDict(adetle.most_common())
#########################

#XML oluşturma ve kaydetme
ana = ET.Element('kelimeler')
for metin, adet in adetle.items():
    if(metin.strip() != ""):
        kelime = ET.SubElement(ana, 'kelime')  
        kelime.set('metin',str(metin))  
        kelime.set('adet',str(adet))
xmlverisi = ET.tostring(ana)  
xmldosya = open(xmldosyasi, "wb")  
xmldosya.write(xmlverisi)  
#########################

#XML okuma
okunacak_xml_dosya = minidom.parse(xmldosyasi)
okunan_kelimeler = okunacak_xml_dosya.getElementsByTagName('kelime')
tablo = PrettyTable()
tablo.field_names = ["Kelime","Kullanım Sayısı"]
say = 0
for node in okunan_kelimeler:
    if(say == xmlden_okunacak_adet): break
    say += 1
    tablo.add_row([node.attributes['metin'].value,node.attributes['adet'].value])
print(tablo)
#########################
