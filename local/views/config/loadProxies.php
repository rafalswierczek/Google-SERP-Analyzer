<?php

require_once "../../libs/db.php";

Db::setContextFromFile("../../db.ini");
$queryResult = Db::query("SELECT * FROM `proxies`", null, "fetchAll");
Db::close();

$proxies = array();
foreach($queryResult as $row)
{
	$proxies[] = array("proxy_id" => $row['proxy_id'], "ip" => $row['ip'], "port" => $row['port'], "user" => $row['user'], "password" => $row['password']);
}

if(!empty($proxies))
{
	require_once "../../libs/simpleDOM.php";

	$formProxy  = new Form("", "POST", null, "formProxy");

	for($i = 0; $i < 10; $i++)
	{
		$div = new Div();

		$rowExists = false;
		foreach($proxies as $row)
		{
			if($row['proxy_id'] == ($i+1))
			{
				$inputStatus = new Input("proxy[$i][status]", "hidden");
				$inputId = new Input("proxy[$i][proxy_id]", "hidden", $row['proxy_id']);
				$inputIp = new Input("proxy[$i][ip]", "text", $row['ip'], null, "proxyInput", "IP address");
				$inputPort = new Input("proxy[$i][port]", "text", $row['port'], null, "proxyInput", "Port");
				$inputUser = new Input("proxy[$i][user]", "text", $row['user'], null, "proxyInput", "User name", 100);
				$inputPassword = new Input("proxy[$i][password]", "text", $row['password'], null, "proxyInput", "User password", 100);
				$buttonEdit = new Button("button", ["proxy", "proxy".($i+1)], "Edytuj", null, "edit");
				$buttonRemove = new Button("button", ["proxy", "proxy".($i+1)], "Usuń", null, "clear");
				$rowExists = true;
				break;
			}
		}

		if(!$rowExists)
		{
			$inputStatus = new Input("proxy[$i][status]", "hidden");
			$inputId = new Input("proxy[$i][proxy_id]", "hidden", $i+1);
			$inputIp = new Input("proxy[$i][ip]", "text", null, null, "proxyInput", "IP address");
			$inputPort = new Input("proxy[$i][port]", "text", null, null, "proxyInput", "Port");
			$inputUser = new Input("proxy[$i][user]", "text", null, null, "proxyInput", "User name", 100);
			$inputPassword = new Input("proxy[$i][password]", "text", null, null, "proxyInput", "User password", 100);
			$buttonEdit = new Button("button", ["proxy", "proxy".($i+1)], "Edytuj", null, "edit");
			$buttonRemove = new Button("button", ["proxy", "proxy".($i+1)], "Usuń", null, "clear");
		}

		$div->appendChild($inputStatus);
		$div->appendChild($inputId);
		$div->appendChild($inputIp);
		$div->appendChild($inputPort);
		$div->appendChild($inputUser);
		$div->appendChild($inputPassword);
		$div->appendChild($buttonEdit);
		$div->appendChild($buttonRemove);
		$formProxy->appendChild($div);
	}

	$submit = new Button(null, null, "Zapisz", "submit");
	$formProxy->appendChild($submit);
	$formProxy->insertElement();
}