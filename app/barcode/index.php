<?php
require 'autoload.php';

// This will output the barcode as HTML output to display in the browser
$generator = new Picqer\Barcode\BarcodeGeneratorHTML();
echo $generator->getBarcode('081231723897', $generator::TYPE_CODE_128);
?>