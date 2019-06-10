<?php

function jsonMessage($type, $message, $elements = null)
{
    $result = ["type" => $type, "body" => $message];
    if($elements)
    {
        foreach($elements as $key => $value)
        {
            $result[$key] = $value;
        }
    }
    return json_encode($result);
}