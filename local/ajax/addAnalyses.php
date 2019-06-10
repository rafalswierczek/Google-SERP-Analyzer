<?php

header('Content-type: application/json; charset=utf-8');

require_once "../libs/jsonMessage.php";
if(!empty($_POST['domain']) && !empty($_POST['phrase']) && !empty($_POST['region']))
{
    require_once "../libs/db.php";

    $domain = $_POST['domain'];
    if(substr($domain, 0, 4) !== "http")
        $domain = "http://$domain";
    if(substr($domain, strlen($domain)-1, 1) === "/" || substr($domain, strlen($domain)-1, 1) === " ")
        $domain = substr($domain, 0, strlen($domain)-1);

    $phrase = $_POST['phrase'];
    $region = $_POST['region'];

    Db::setContextFromFile("../db.ini");
    $query = <<<QUERY
        SELECT
            p.proxy_id,
            COUNT(a.proxy_id) AS analyses
        FROM
            `proxies` AS p
        LEFT JOIN `analyses` AS a ON a.proxy_id = p.proxy_id
        GROUP BY p.proxy_id
QUERY;

    $analysesCount = Db::query($query, null, "fetchAll");

    $query = <<<QUERY
        SELECT MIN(unused) AS minId
        FROM (
            SELECT MIN(t1.analysis_id)+1 AS unused
            FROM `analyses` AS t1
            WHERE NOT EXISTS (SELECT * FROM `analyses` AS t2 WHERE t2.analysis_id = t1.analysis_id+1)
            UNION
            -- Special case for missing the first row
            SELECT 1
            FROM DUAL
            WHERE NOT EXISTS (SELECT * FROM `analyses` WHERE analysis_id = 1)
        ) AS subquery
QUERY;

    $analysisMinId = Db::query($query, null, "fetch")['minId'];

    $addedAnalysisToProxy = false;
    foreach($analysesCount as $row) // foreach existing proxy ($analysesCount['proxy_id'])
    {
        if($row['analyses'] < 8)
        {
            $query = <<<QUERY
                INSERT INTO `analyses` (analysis_id, domain, phrase, country_code, proxy_id) 
                VALUES (:analysis_id, :domain, :phrase, :region, :proxy_id)
QUERY;
            $executeArray = array("analysis_id" => $analysisMinId, "domain" => $domain, "phrase" => $phrase, "region" => $region, "proxy_id" => $row['proxy_id']);
            Db::query($query, $executeArray);
            $addedAnalysisToProxy = true;

            echo jsonMessage("result", "Analiza z id: $analysisMinId została dodana.");
            break; 
        }
    }
    Db::close();

    if(!$addedAnalysisToProxy)
        echo jsonMessage("error", "Nie można dodać analizy. Wszystkie serwery proxy są zapełnione analizami. Dodaj nowy proxy, lub usuń istniejącą analizę.");
}
else
    echo jsonMessage("error", "Nie można dodać analizy. Niewystarczająca ilość danych z formularza.");