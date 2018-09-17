<?php
include 'baglanti.php';

if(@$_POST["gonder"]){
    function cevir($text) {
        $text = trim($text);
        $search = array('ç','ğ','ı','ö','ş','ü','i');
        $replace = array('Ç','Ğ','I','Ö','Ş','Ü','İ');
        $new_text = str_replace($search,$replace,$text);
        return mb_strtoupper($new_text);
    }
    function tcno_dogrula($bilgiler){
        $gonder = '<?xml version="1.0" encoding="utf-8"?>
<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
<soap:Body>
<TCKimlikNoDogrula xmlns="http://tckimlik.nvi.gov.tr/WS">
<TCKimlikNo>'.$bilgiler["tcno"].'</TCKimlikNo>
<Ad>'.$bilgiler["isim"].'</Ad>
<Soyad>'.$bilgiler["soyisim"].'</Soyad>
<DogumYili>'.$bilgiler["dogumyili"].'</DogumYili>
</TCKimlikNoDogrula>
</soap:Body>
</soap:Envelope>';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://tckimlik.nvi.gov.tr/Service/KPSPublic.asmx" );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt($ch, CURLOPT_POST, true );
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $gonder);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'POST /Service/KPSPublic.asmx HTTP/1.1',
            'Host: tckimlik.nvi.gov.tr',
            'Content-Type: text/xml; charset=utf-8',
            'SOAPAction: "http://tckimlik.nvi.gov.tr/WS/TCKimlikNoDogrula"',
            'Content-Length: '.strlen($gonder)
        ));
        $gelen = curl_exec($ch);
        curl_close($ch);
        return strip_tags($gelen);
    }
    $bilgiler = array(
        "isim" => cevir($_POST["isim"]),
        "soyisim" => cevir($_POST["soyisim"]),
        "dogumyili" => $_POST["dogumyili"],
		"mail" => $_POST["mail"],
        "tcno" => $_POST["tcno"],
		"sifre" => $_POST["sifre"],
    );
    $sonuc = tcno_dogrula($bilgiler);
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <title>xGame Üye Kaydı.</title>
    <meta name='viewport' content='width=device-width, initial-scale=1.0, maximum-scale=1.0'>
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />
    <style type="text/css">
        .container { max-width: 600px !important; }
    </style>
</head>
<body>
<div class="container">
    <div class="row">
        <center><h2>xGame Kullanıcı Kayıt Formu</h2></center>
        <hr />
        <?php if(@$_POST["gonder"]){
            if(@$sonuc=="true"){
				//
				
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

				
				
				//
				
				
				
				
                echo '<div class="alert alert-success"><strong>BAŞARILI</strong> Bilgiler eşleşti! Kayıt başarılı, giriş yapabilirsiniz.</div>';
            }else{
                echo '<div class="alert alert-danger"><strong>HATA!</strong> Bilgiler uyuşmadı!</div>';
            }
            ?>
            <hr />
        <?php } ?>
        <form class="form-horizontal" method="post" action="index.php" id="form">
            <div class="form-group">
                <label for="tid" class="col-sm-2 control-label">İsim</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="isim" placeholder="Adınız." value="<?php if(isset($_POST["isim"])){ echo cevir($_POST["isim"]);}?>" required />
                </div>
            </div>
            <div class="form-group">
                <label for="ck" class="col-sm-2 control-label">Soyisim</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="soyisim" placeholder="Soyadınız." value="<?php if(isset($_POST["soyisim"])){ echo cevir($_POST["soyisim"]);}?>" required />
                </div>
            </div>
            <div class="form-group">
                <label for="dh" class="col-sm-2 control-label">Doğum Yılı</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="dogumyili" placeholder="Doğum yılınız." value="<?php if(isset($_POST["dogumyili"])){ echo $_POST["dogumyili"];}?>" required />
                </div>
            </div>
			
			<div class="form-group">
                <label for="mail" class="col-sm-2 control-label">E-Mail</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="mail" placeholder="Mail Adresiniz." value="<?php if(isset($_POST["mail"])){ echo cevir($_POST["mail"]);}?>" required />
                </div>
            </div>
			
			
            <div class="form-group">
                <label for="dp" class="col-sm-2 control-label">TC No</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="tcno" placeholder="TC Kimlik numaranızı girin" value="<?php if(isset($_POST["tcno"])){ echo $_POST["tcno"];}?>" required />
                </div>
            </div>
			
			<div class="form-group">
                <label for="sifre" class="col-sm-2 control-label">Şifreniz</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="sifre" placeholder="Yeni Hesap Şifrenizi belirtin." value="<?php if(isset($_POST["sifre"])){ echo cevir($_POST["sifre"]);}?>" required />
                </div>
            </div>
			
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <input type="submit" name="gonder" class="btn btn-success" value="Kayıt Ol">
                </div>
            </div>
        </form>
        <hr />
    </div>
</div>
</div>
</body>
</html>