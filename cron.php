<?php

function GetMemoryUsage($size)
{
    $unit=array('B','KB','MB','GB','TB','PB');
    return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
}

echo GetMemoryUsage(memory_get_usage()).PHP_EOL;

$c = curl_init();
curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($c, CURLOPT_HEADER, 0);
curl_setopt($c, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($c, CURLOPT_URL, "https://www.google.com/search?q=phr&ie=utf-8&oe=utf-8&num=100&client=firefox-b-ab");

$pages = curl_exec($c);
curl_close($c);

echo GetMemoryUsage(memory_get_usage()).PHP_EOL;