<?php
// check.php?url=https://veterland.ru
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$url = $_GET['url'] ?? '';
if (!filter_var($url, FILTER_VALIDATE_URL)) {
    echo json_encode(['error' => 'bad url']);
    exit;
}

$ch = curl_init($url);
curl_setopt_array($ch, [
    CURLOPT_NOBODY         => true,   // HEAD-запрос, тело не тянем
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_TIMEOUT        => 5,
    CURLOPT_CONNECTTIMEOUT => 4,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_SSL_VERIFYHOST => false,
    CURLOPT_USERAGENT      => 'VeterLand-StatusBot/1.0',
]);

curl_exec($ch);

if (curl_errno($ch)) {
    curl_close($ch);
    echo json_encode(['status' => 0, 'error' => 'unreachable']);
    exit;
}

$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo json_encode(['status' => $code]);
