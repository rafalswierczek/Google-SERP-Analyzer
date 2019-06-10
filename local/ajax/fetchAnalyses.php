<?php

header('Content-type: application/json; charset=utf-8');

if(!empty($_GET['fetch']) && $_GET['fetch'] === "true")
{
    require_once "../libs/db.php";
    require_once "../libs/jsonMessage.php";

    try
    {
        Db::setContextFromFile("../db.ini");
        $queryResult = Db::query("SELECT * FROM `analyses`", null, "fetchAll");
        Db::close();

        echo jsonMessage("result", $queryResult);
    }
    catch(Exception $e)
    {
        echo jsonMessage("error", "Nie można pobrać analiz. ". $e->getMessage());
    }
}