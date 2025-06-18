<?php
header('Content-Type: application/vnd.apple.mpegurl');
header('Access-Control-Allow-Origin: *');

$videoId = $_GET['videoId'] ?? '';
$referer = 'https://abwaab.com';

if (empty($videoId) || !preg_match('/^[a-f0-9-]{36}$/i', $videoId)) {
    header('HTTP/1.1 400 Bad Request');
    die('كود الفيديو غير صالح');
}

$qualities = ['1080p', '720p', '480p', '360p'];
$found = false;

foreach ($qualities as $quality) {
    $url = "https://vz-99e5c202-ca5.b-cdn.net/{$videoId}/{$quality}/video.m3u8";
    
    $options = [
        'http' => [
            'header' => "Referer: {$referer}\r\n" .
                        "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36\r\n",
            'timeout' => 5
        ]
    ];
    
    $context = stream_context_create($options);
    $content = @file_get_contents($url, false, $context);
    
    if ($content !== false) {
        // استبدل روابط القطع TS لتمر عبر البروكسي أيضاً
        $content = preg_replace_callback('/^(.*\.ts)/m', function($matches) use ($videoId) {
            return "proxy_ts.php?videoId={$videoId}&ts=" . urlencode($matches[1]);
        }, $content);
        
        echo $content;
        $found = true;
        break;
    }
}

if (!$found) {
    header('HTTP/1.1 404 Not Found');
    die('لم يتم العثور على الفيديو');
}
?>