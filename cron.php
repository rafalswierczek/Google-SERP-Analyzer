<?php
// 'Accept-Language: pl-PL,pl;q=0.9,en-US;q=0.8,en;q=0.7',
$proxySettings = array(
	"ip" => "145.239.82.182:20186:1394_2335:uY7Dm2VApo",
	"user" => "1394_2335",
	"password" => "uY7Dm2VApo",
	"port" => 24000,
	"headers" => [
		'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3',
		'Accept-Language: pl-PL,pl;q=0.9,en-US;q=0.8,en;q=0.7',
		'Cache-Control: no-cache',
		'Connection: keep-alive',
		'Host: www.google.com',
		'Upgrade-Insecure-Requests: 1',
		'User-Agent: Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/73.0.3683.86 Safari/537.36',
		'Content-Type: application/x-www-form-urlencoded; charset=utf-8'
	]
);

$c = curl_init();
curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
curl_setopt($c, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($c, CURLOPT_MAXREDIRS, 8);
curl_setopt($c, CURLOPT_HEADER, true);
curl_setopt($c, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($c, CURLOPT_PROXY, $proxySettings['ip']);
//curl_setopt($c, CURLOPT_PROXYPORT, $proxySettings['port']);
curl_setopt($c, CURLOPT_PROXYUSERPWD, "{$proxySettings['user']}:{$proxySettings['password']}");
curl_setopt($c, CURLOPT_HTTPHEADER, $proxySettings['headers']);
curl_setopt($c, CURLOPT_URL, "https://www.google.com/search?q=pizza&ie=utf-8&oe=utf-8&num=100&client=firefox-b-ab&hl=en&gl=en&lr=lang_en&cr=countryUK");

$pages = curl_exec($c);
$httpcode = curl_getinfo($c, CURLINFO_HTTP_CODE); // 200 jest dobre
curl_close($c);

echo "IP: <b>{$proxySettings['ip']}</b><br><br><br>";
echo $pages;