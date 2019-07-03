<?php
error_reporting(E_ALL); // Olurda hata yaparsak bir görelim

//Değişkenler
$veri_adresi = "http://www.gutenberg.org/cache/epub/2489/pg2489.txt";
$xmldosyasi = "cikti.xml";
$xmlden_okunacak_adet = 10;
/////////////////////////////////////////

//CURL ile veriyi çekme
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $veri_adresi);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "Content-Type: application/x-www-form-urlencoded",
    "Upgrade-Insecure-Requests: 1",
    "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/75.0.3770.100 Safari/537.36",
    "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3",
    "Accept-Language: tr-TR,tr;q=0.9,en-US;q=0.8,en;q=0.7",
));
$metin = curl_exec($ch);
if(!$metin) die("Veri çekilemedi.");
$veri_cekme_bilgisi = curl_getinfo($ch);
echo "Veri <a href='".$veri_adresi."' target='_blank'>adresten</a> çekildi. (".$veri_cekme_bilgisi['total_time']." saniye sürdü. Sunucu dönüş kodu: ".$veri_cekme_bilgisi["http_code"].")<br>";
curl_close($ch);
/////////////////////////////////////////

//Çekilen veriyi parçalayıp sıralama
$kelimeler =  explode(" ",$metin);
$adetle = array_count_values($kelimeler);
arsort($adetle);
/////////////////////////////////////////

//XML oluşturma ve kaydetme
$dom = new DomDocument('1.0', 'UTF-8');
$ana = $dom->appendChild($dom->createElement('kelimeler'));

foreach ($adetle as $kelime => $adet){
    if(!empty(trim($kelime))) {
        $eleman = $dom->createElement('kelime');
        $ana->appendChild($eleman);
        $bilgi = $dom->createAttribute('metin');
        $bilgi->appendChild($dom->createTextNode($kelime));
        $eleman->appendChild($bilgi);
        $bilgi = $dom->createAttribute('adet');
        $bilgi->appendChild($dom->createTextNode($adet));
        $eleman->appendChild($bilgi);
    }
}

$dom->formatOutput = true;
$test1 = $dom->saveXML();
if($dom->save($xmldosyasi)) echo 'XML kaydedildi. (<a href="'.$xmldosyasi.'" target="_blank">Göster</a>)<br>';
else die("Hata: XML dosyası kaydedilemedi.");
/////////////////////////////////////////


//XML okuma
$xml=simplexml_load_string(file_get_contents($xmldosyasi)) or die("Hata: XML dosyası okunamadı.");
echo "XML okundu.<br>";
?>
<style>
    table, th, td {
        border: 1px solid black;
    }
</style>
<table>
    <tr>
        <th>Kelime</th>
        <th>Kullanım Sayısı</th>
    </tr>
    <?php
    $say = 0;
    foreach ($xml as $node){
        $say++;
        if($say == $xmlden_okunacak_adet) break;
        echo '<tr>
        <td>'.$node["metin"].'</td>
        <td>'.$node["adet"].'</td>
    </tr>';
    }
    /////////////////////////////////////////
    ?>
</table>
