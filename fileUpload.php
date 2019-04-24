<?php
/** @file fileUpload.php
*
* This file sends PDF to API and downloads converted HTML
*
*/

$c = curl_init();
if (!function_exists('curl_file_create')) {
    function curl_file_create($filename, $mimetype = '', $postname = '') {
        return "@$filename;filename="
            . ($postname ?: basename($filename))
            . ($mimetype ? ";type=$mimetype" : '');
    }
}
$filePath = $_POST['filePath'];
$cfile = curl_file_create($filePath, 'application/pdf');
$apikey = $_POST['key'];// from https://pdftables.com/api
curl_setopt($c, CURLOPT_URL, "https://pdftables.com/api?key=$apikey&format=html");
curl_setopt($c, CURLOPT_POSTFIELDS, array('file' => $cfile));
curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
curl_setopt($c, CURLOPT_FAILONERROR,true);
curl_setopt($c, CURLOPT_ENCODING, "gzip,deflate");
$result = curl_exec($c);
if (curl_errno($c) > 0) {
    print('Error calling PDFTables: '.curl_error($c).PHP_EOL);
} else {
    $myfilename='pdf'.'.html';
    $dataForFile=$result;
    header('Content-type: text/html');
    header('Content-Disposition: attachment; filename="'.$myfilename.'"');
    header('Content-Transfer-Encoding: binary');
    header('Content-Length: '.strlen($dataForFile));
    set_time_limit(0);
    echo $dataForFile;
    exit;
}
curl_close($c);
?>