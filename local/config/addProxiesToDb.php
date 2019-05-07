<?php

if(isset($_POST['proxy1Ip']))
{
	$currentUrl = explode("?", $_SERVER['REQUEST_URI'])[0];
	require_once "../db.php";

	$context = Db::getContextFromFile("../db.ini");
	if($context)
	{
		$queryResult = Db::query("SELECT * FROM `proxies`", "fetchAll");

		$proxies = array();
		foreach($queryResult as $row)
		{
			$proxies[$row['proxy_id']] = array("ip" => $row['ip'], "port" => $row['port']);
		}
	

		$proxyForm = array(
			array(htmlspecialchars($_POST["proxy1Ip"]), htmlspecialchars($_POST["proxy1Port"])),
			array(htmlspecialchars($_POST["proxy2Ip"]), htmlspecialchars($_POST["proxy2Port"])),
			array(htmlspecialchars($_POST["proxy3Ip"]), htmlspecialchars($_POST["proxy3Port"])),
			array(htmlspecialchars($_POST["proxy4Ip"]), htmlspecialchars($_POST["proxy4Port"])),
			array(htmlspecialchars($_POST["proxy5Ip"]), htmlspecialchars($_POST["proxy5Port"])),
			array(htmlspecialchars($_POST["proxy6Ip"]), htmlspecialchars($_POST["proxy6Port"])),
			array(htmlspecialchars($_POST["proxy7Ip"]), htmlspecialchars($_POST["proxy7Port"])),
			array(htmlspecialchars($_POST["proxy8Ip"]), htmlspecialchars($_POST["proxy8Port"])),
			array(htmlspecialchars($_POST["proxy9Ip"]), htmlspecialchars($_POST["proxy9Port"])),
			array(htmlspecialchars($_POST["proxy10Ip"]), htmlspecialchars($_POST["proxy10Port"]))
		);

		$deleteRows = array();
		$insertRows = array();
		foreach($proxyForm as $index => $row)
		{
			$proxyFormId = $index+1;

			if(empty($row[0]) || empty($row[1]))
			{
				if(isset($proxies[$proxyFormId]))
				{
					$deleteRows[] = $proxyFormId;
				}
			}
			else
				$insertRows[] = $proxyFormId;
		}

		if(count($deleteRows) > 0)
		{
			$queryString = "DELETE FROM `proxies` WHERE proxy_id IN (";

			foreach($deleteRows as $index => $id)
			{
				$queryString .= "'$id'";
				$rows = count($deleteRows);

				if($rows > 1 && $index+1 < $rows) // put 'and' only if 2 or more proxies and not for last proxy
				{
					$queryString .= ", ";
				}
			}
			$queryString .= ")";

			Db::query($queryString);
		}

		if(count($insertRows) > 0)
		{
			$queryString = "INSERT INTO `proxies` (proxy_id, ip, port) VALUES ";

			foreach($insertRows as $id)
			{
				$queryString .= "('$id', '".implode("', '", $proxyForm[$id-1])."'), ";
			}
			$queryString = substr($queryString, 0, -2);
			$queryString .= " ON DUPLICATE KEY UPDATE ip = VALUES(ip), port = VALUES(port)";

			Db::query($queryString);
		}

		echo "true";
	}
}