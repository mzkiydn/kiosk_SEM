<?php

require "../vendor/autoload.php";

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Label\Alignment\LabelAlignmentLeft;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;

$text = $_POST["text"];

$qr_code = QrCode::create($text)->setSize(600)
                                ->setMargin(40);

$writer = new PngWriter;

$result = $writer->write($qr_code);

header("Content-Type: ". $result->getMimeType());

echo $result->getString();

?>