<?php

header('Content-type: application/json; charset=utf-8');

if(!empty($_GET['id']))
{
    if((int)$_GET['id'])
    {
        require_once "../libs/db.php";
        require_once "../libs/jsonMessage.php";

        try
        {
            Db::setContextFromFile("../db.ini");
            Db::query("DELETE FROM `analyses` WHERE analysis_id = ?", [$_GET['id']]);
            Db::close();
            
            echo jsonMessage("result", "Analiza z id: {$_GET['id']} została usunięta.");
        }
        catch(Exception $e)
        {
            echo jsonMessage("error", "Nie można usunąć analizy z id: {$_GET['id']} | ". $e->getMessage());
        }
    }
}