<?php

if(!empty($_POST['analysis_id']))
{
    require_once "../libs/db.php";
    
    Db::setContextFromFile("../db.ini");
    $data = Db::query("SELECT * FROM `datastorage` WHERE analysis_id = ?", [$_POST['analysis_id']], "fetchAll");
    if(!empty($data))
    {
        $output = '[';
        foreach($data as $row)
        {
            $row['timestamp'] = strtotime($row["date"]." ".$row["time"]);
            $output .= "[{$row['timestamp']}, {$row['position']}], ";
        }
        $output = rtrim($output, ", ");
        $output .= "]";
        echo $output;
        //echo json_encode($output);//JSON_UNESCAPED_UNICODE
    }
    else
        echo json_encode("Jeszcze nie pobrano danych z Google!");
}