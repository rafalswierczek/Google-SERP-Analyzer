<?php

header('Content-type: application/json; charset=utf-8');

if(!empty($_POST['analysis_id']))
{
    require_once "../libs/db.php";

    Db::setContextFromFile("../db.ini");
    $data = Db::query("SELECT * FROM `datastorage` WHERE analysis_id = ?", [$_POST['analysis_id']], "fetchAll");

    echo json_encode($data);
}