<?php
header('Content-Type: video/mp2t');
header('Access-Control-Allow-Origin: *');

$videoId = $_GET['videoId'] ?? '';
$tsUrl = $_GET['ts'] ?? '';
$referer = 'https://abwaab.com';

if (empty($videoId) || empty($tsUrl)) {
    header('HTTP/1.1 400 Bad Request');
    die('معلمات غير صالحة');
}

$tsUrl = urldecode($tsUrl);

// التحقق من أن رابط TS ينتمي لنفس الفيديو
if (strpos($tsUrl, $videoId) === false) {
    header('HTTP/1.1 403 Forbidden');
    die('رابط غير مسموح');
}

$options = [
    'http' => [
        'header' => "Referer: {$referer}\r\n" .
                    "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36\r\n",
        'timeout' => 10
    ]
];

$context = stream_context_create($options);
$content = @file_get_contents($tsUrl, false, $context);

if ($content === false) {
    header('HTTP/1.1 500 Internal Server Error');
    die('فشل في جلب المقطع');
}

echo $content;
?>