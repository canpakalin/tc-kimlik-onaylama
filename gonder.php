<?php
$isim = $_POST['isim'];
$mail = $_POST['mail'];
$tcno = $_POST['tcno'];
$sifre = $_POST['sifre'];

if(empty($isim)){
echo("<center><b>İsim Yazmadınız. Lütfen Geri Dönüp Doldurunuz.</b></center>");
}elseif(empty($mail)){
echo("<center><b>mail Yazmadınız. Lütfen Geri Dönüp Doldurunuz.</b></center>");
}elseif(empty($tcno)){
echo("<center><b>Tc Yazmadınız. Lütfen Geri Dönüp Doldurunuz.</b></center>");
}elseif(empty($sifre)){
echo("<center><b>Şifre Yazmadınız. Lütfen Geri Dönüp Doldurunuz.</b></center>");
}else{
include("baglanti.php");
$sql = "insert into kullanicilar (isim, mail, tcno, sifre)
values ('$isim', '$mail', '$tcno', '$sifre')";
$kayit = mysql_query($sql);
}
if (isset ($kayit)){
echo "Üye Kaydınız Yapılmıştır";
}
else {
echo "Kayıt Başarısız sanane@banane.com adresinden iletişime geçin";
}




?>