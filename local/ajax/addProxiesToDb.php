<?php

header('Content-type: application/json; charset=utf-8');

require_once "../libs/jsonMessage.php";
if(!empty($_POST['proxy']))
{
	require_once "../libs/db.php";
	
	Db::setContextFromFile("../db.ini");
	$queryResult = Db::query("SELECT * FROM `proxies`", null, "fetchAll");

	$proxies = array();
	foreach($queryResult as $row)
	{
		$proxies[$row['proxy_id']] = array("ip" => $row['ip'], "port" => $row['port']);
	}

	$proxyForm = array();
	foreach($_POST['proxy'] as $proxyRow)
	{
		$proxyForm[] = array("status" => $proxyRow['status'], "proxy_id" => $proxyRow['proxy_id'], "ip" => $proxyRow['ip'], "port" => $proxyRow['port'], "user" => $proxyRow['user'], "password" => $proxyRow['password']);
	}

	$deleteRows = array();
	$insertRows = array();
	$analyses = Db::query("SELECT proxy_id FROM `analyses`", null, "fetchAll");

	foreach($proxyForm as $index => $row)
	{
		if($row['status'] === "delete")
		{
			if(!empty($proxies[$row['proxy_id']]))
			{
				$delete = true;
				foreach($analyses as $analysis)
				{
					if($analysis['proxy_id'] === $row['proxy_id'])
					{
						$delete = false;
						break;
					}
				}
				if($delete)
					$deleteRows[] = $row['proxy_id'];
				else
					die(jsonMessage("error", "Nie można usunąć adresu proxy z id: {$row['proxy_id']}, ponieważ jest używany przez analizę.", ["proxy_id" => $row['proxy_id']]));
			}
			else
				die(jsonMessage("error", "Nie można usunąć adresu proxy z id: {$row['proxy_id']}, ponieważ nie ma go w bazie danych."));
		}
		else
		{
			if(!empty($row['ip']) && !empty($row['port']) && !empty($row['user']) && !empty($row['password']))
			{
				$insertRows[] = array("proxy_id" => $row['proxy_id'], "ip" => $row['ip'], "port" => $row['port'], "user" => $row['user'], "password" => $row['password']);	
			}
		}
			
	}

	$rows = count($deleteRows);
	if($rows > 0)
	{
		$queryString = "DELETE FROM `proxies` WHERE proxy_id IN (";

		for($i = 0; $i < $rows; $i++)
		{
			$queryString .= "?";
			
			if($rows > 1 && $i+1 < $rows)
			{
				$queryString .= ", ";
			}
		}
		$queryString .= ")";

		Db::query($queryString, $deleteRows);
	}

	$rows = count($insertRows);
	if($rows > 0)
	{
		$queryString = "INSERT INTO `proxies` (proxy_id, ip, port, user, password) VALUES ";

		for($i = 0; $i < $rows; $i++)
		{
			$queryString .= "(?, ?, ?, ?, ?), ";
		}
		$queryString = substr($queryString, 0, -2);
		$queryString .= " ON DUPLICATE KEY UPDATE proxy_id = VALUES(proxy_id), ip = VALUES(ip), port = VALUES(port), user = VALUES(user), password = VALUES(password)";
		$executeArray = array();
		foreach($insertRows as $row)
		{
			foreach($row as $key => $value)
			{
				$executeArray[] = $value;
			}
		}

		try
		{
			Db::query($queryString, $executeArray);
		}
		catch (PDOException $e)
		{
			if ($e->errorInfo[1] == 1062)
				die(jsonMessage("error", "Adres IP serwera proxy już istnieje."));
			else
			   die($e->getMessage());
		 }
	}
	Db::close();

	echo jsonMessage("result", "Zapisano zmiany w bazie danych.");
}