<?php

$proxySettings = array(
	"proxyIP" => "145.239.82.182:20184:1394_2335:uY7Dm2VApo",
	"user" => "1394_2335",
	"password" => "uY7Dm2VApo",
	"proxyPort" => 24000
);


$c = curl_init();

curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
curl_setopt($c, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($c, CURLOPT_MAXREDIRS, 8);
curl_setopt($c, CURLOPT_HEADER, true);
curl_setopt($c, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
//curl_setopt($c, CURLOPT_PROXY, $proxySettings['proxyIP']);
//curl_setopt($c, CURLOPT_PROXYPORT, $proxySettings['proxyPort']);
//curl_setopt($c, CURLOPT_PROXYUSERPWD, "{$proxySettings['user']}:{$proxySettings['password']}");
curl_setopt($c, CURLOPT_URL, "https://www.google.com/search?q=wtf&ie=utf-8&oe=utf-8&num=10&client=firefox-b-ab");

$pages = curl_exec($c);
$httpcode = curl_getinfo($c, CURLINFO_HTTP_CODE);
curl_close($c);

echo $httpcode;
echo $pages;