<?php

// &num=100 nie daje realnych wyników
// todo: wdrożyć requestowanie paginacji serp
// stary cron to 100% realności + nie zwiększa ryzyka bana, gdyż requestowana jest ta sama fraza, a zmienia się tylko &start=
// kod:
/*
for() // dla wszystkich analiz
{
    $j = 0;
    $lastPos = 0;
    $pages = "";
    $next_page = true;
    while($next_page) // dla danej frazy
    {
        $c = curl_init();
        ...
        curl_setopt($c, CURLOPT_URL, "https://www.google.com/search?q=".str_replace(" ", "+", $config_table[$i]['phrase'])."&ie=utf-8&oe=utf-8&start=".($j*10)."&client=firefox-b-ab");

        $pages .= curl_exec($c);
        if (($lastPos = strpos($pages, 'left:53px', $lastPos+100)) === false || $j === 9) // jeżeli nie istnieje element <span >Następna</span> lub badana jest 10-ta strona
        {
            $next_page = false;
        }
        $j++;
        curl_close($c);

        $rand = mt_rand(2,6);
        $sleeptime += $rand;
        sleep($rand);
    }
...
}
*/

$start = microtime(true);
$sleeptime = 0;

require_once "libs/db.php";

Db::setContextFromFile("db.ini");
$query = <<<QUERY
SELECT
	a.domain,
	a.phrase,
	a.country_code,
	a.proxy_id,
	p.ip,
	p.port,
	p.user,
	p.password
FROM `analyses` AS a
INNER JOIN `proxies` AS p
ON a.proxy_id = p.proxy_id
QUERY;
$analysesResult = Db::query($query, null, "fetchAll");

foreach($analysesResult as $analysis)
{
    // User-Agent | aktualizacja: 25.06.2019
    $headers = [
        'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3',
        'Accept-Language: pl-PL,pl;q=0.9,en-US;q=0.8,en;q=0.7',
        'Cache-Control: no-cache',
        'Connection: keep-alive',
        'Host: www.google.com',
        'Upgrade-Insecure-Requests: 1',
        'User-Agent: Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/75.0.3770.100 Safari/537.36',
        'Content-Type: application/x-www-form-urlencoded; charset=utf-8'
    ];

    $c = curl_init();
    curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($c, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($c, CURLOPT_MAXREDIRS, 5);
    curl_setopt($c, CURLOPT_HEADER, true);
    curl_setopt($c, CURLOPT_HEADEROPT, CURLHEADER_UNIFIED);
    curl_setopt($c, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($c, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
    if($analysis['proxy_id'] !== 0)
    {
        curl_setopt($c, CURLOPT_PROXY, $analysis['ip']);
        curl_setopt($c, CURLOPT_PROXYPORT, $analysis['port']);
        curl_setopt($c, CURLOPT_PROXYUSERPWD, "{$analysis['user']}:{$analysis['password']}");
    }
    $urlPhrase = str_replace(" ", "+", $analysis['phrase']);
    if($analysis['country_code'] === "en")
        $url = "https://www.google.com/search?q=$urlPhrase&ie=utf8&oe=utf8&num=100&hl=en&gl=en&lr=lang_en"; // cr=countryUK zmienia wyniki
    else if($analysis['country_code'] === "pl")
        $url = "https://www.google.com/search?q=$urlPhrase&ie=utf8&oe=utf8&num=100&hl=pl&gl=pl&lr=lang_pl"; // cr=countryPL zmienia wyniki
    curl_setopt($c, CURLOPT_URL, $url);

    $pages = curl_exec($c);

    if(curl_errno($c))
    {
        $datetime = date('d.m.Y - G:i:s');
        $currentDir = dirname($_SERVER["REQUEST_URI"]);
        if(substr($currentDir, strlen($currentDir)-1) !== "/")
            $currentDir .= "/";
            
        $curlLog = fopen(__DIR__."/curlLog.log", "a"); // absolute: __DIR__."/curlLog.log" | relative: "curlLog.log"
        if($analysis['proxy_id'] !== 0)
            fwrite($curlLog, "[$datetime] | [IP: {$analysis['ip']}:{$analysis['port']}] | [USER: {$analysis['user']}] | [PASSWORD: {$analysis['password']}] | ERROR: ".curl_error($c).PHP_EOL);
        else
            fwrite($curlLog, "[$datetime] | ERROR: ".curl_error($c).PHP_EOL);
        
        fclose($curlLog);

        die(curl_error($c));
    }
    $httpCode = curl_getinfo($c, CURLINFO_HTTP_CODE);
    if($httpCode !== 200)
        die($httpCode);
    curl_close($c);
    
    $lastPos = 0;
    $domains = array();

    while(($lastPos = strpos($pages, 'class="r"><a href="', $lastPos+1)) !== false) // $lastPos+1 => +1: inf loop fix
    {
        $lastPos = $lastPos + strlen('class="r"><a href="');
        $domains[] = substr($pages, $lastPos, strpos($pages, "/", $lastPos+8)-$lastPos);
    }

    if(!empty($domains))
    {
        for($d = 0; $d < count($domains); $d++)
        {
            if($analysis['domain'] === $domains[$d])
            {
                $date = date('Y-m-d');
                $time = date("H:i:s");
                $position = $d+1;

                $executeArray = array($analysis['domain'], $analysis['phrase'], $position, $date, $time);
                Db::query("INSERT INTO `datastorage` (domain,phrase,position,date,time) VALUES (?, ?, ?, ?, ?)", $executeArray);
                break;
            }
        }
    }
    else
    {
        $datetime = date('d.m.Y - G:i:s');
        $curlLog = fopen(__DIR__."/curlLog.log", "a"); // absolute: __DIR__."/curlLog.log" | relative: "curlLog.log"
        fwrite($curlLog, "[$datetime] | Nie można znaleźć domeny `{$analysis['domain']}` w SERP dla frazy `{$analysis['phrase']}`");
        fclose($curlLog);
    }

    $randSleep = mt_rand(8,20);
    $sleeptime += $randSleep;
    sleep($randSleep);
}

$overall_time = microtime(true) - $start;
echo "execution time: ".($overall_time-$sleeptime)."s <br><br>"."sleep time: ".$sleeptime."s <br><br>"."overall time: ".$overall_time."s";