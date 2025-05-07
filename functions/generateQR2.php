<?php 

require_once '../assets/vendor/phpqrcode/qrlib.php';

$pathQr = '../assets/img/qr/';
$qrCode = $pathQr.time().".png";
QRcode :: png("Test",$qrCode,'H',4,4 );
echo "<img src='".$qrCode."'>";

?>