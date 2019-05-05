<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Proxy</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<script src="../scripts/addProxiesToDb.js"></script>
</head>
<body>
<a href="../index.php">Logo? / Strona główna</a><br>

<?php

	$currentUrl = explode("?", $_SERVER['REQUEST_URI'])[0];
	require_once "../db.php";

	$context = Db::getContextFromFile("../db.ini");
	if($context)
	{
		$queryResult = Db::query("SELECT * FROM `proxies`", "fetchAll");

		$proxies = array();
		foreach($queryResult as $row)
		{
			$proxies[$row['id']] = array("ip" => $row['ip'], "port" => $row['port']);
		}
	}


?>

<h1>Konfiguracja aplikacji</h1>
<p>Dodanie adresów serwerów proxy</p>

<form id="formProxy" action="" method="POST">
	<label for="proxy1Ip">1 adres serwera proxy</label>
	<input id="proxy1Ip" type="text" name="proxy1Ip" placeholder="255.255.255.255" value="<?php if(!empty($proxies) && array_key_exists(1, $proxies)) echo $proxies[1]['ip']; ?>">
	port <input id="proxy1Port" type="text" name="proxy1Port" placeholder="8080" value="<?php if(!empty($proxies) && array_key_exists(1, $proxies)) echo $proxies[1]['port']; ?>">
	<button class="clear" data-clear="proxy1" type="button">X</button><br>

	<label for="proxy2Ip">2 adres serwera proxy</label>
	<input id="proxy2Ip" type="text" name="proxy2Ip" placeholder="255.255.255.255" value="<?php if(!empty($proxies) && array_key_exists(2, $proxies)) echo $proxies[2]['ip']; ?>">
	port <input id="proxy2Port" type="text" name="proxy2Port" placeholder="8080" value="<?php if(!empty($proxies) && array_key_exists(2, $proxies)) echo $proxies[2]['port']; ?>">
	<button class="clear" data-clear="proxy2" type="button">X</button><br>

	<label for="proxy3Ip">3 adres serwera proxy</label>
	<input id="proxy3Ip" type="text" name="proxy3Ip" placeholder="255.255.255.255" value="<?php if(!empty($proxies) && array_key_exists(3, $proxies)) echo $proxies[3]['ip']; ?>">
	port <input id="proxy3Port" type="text" name="proxy3Port" placeholder="8080" value="<?php if(!empty($proxies) && array_key_exists(3, $proxies)) echo $proxies[3]['port']; ?>">
	<button class="clear" data-clear="proxy3" type="button">X</button><br>

	<label for="proxy4Ip">4 adres serwera proxy</label>
	<input id="proxy4Ip" type="text" name="proxy4Ip" placeholder="255.255.255.255" value="<?php if(!empty($proxies) && array_key_exists(4, $proxies)) echo $proxies[4]['ip']; ?>">
	port <input id="proxy4Port" type="text" name="proxy4Port" placeholder="8080" value="<?php if(!empty($proxies) && array_key_exists(4, $proxies)) echo $proxies[4]['port']; ?>">
	<button class="clear" data-clear="proxy4" type="button">X</button><br>

	<label for="proxy5Ip">5 adres serwera proxy</label>
	<input id="proxy5Ip" type="text" name="proxy5Ip" placeholder="255.255.255.255" value="<?php if(!empty($proxies) && array_key_exists(5, $proxies)) echo $proxies[5]['ip']; ?>">
	port <input id="proxy5Port" type="text" name="proxy5Port" placeholder="8080" value="<?php if(!empty($proxies) && array_key_exists(5, $proxies)) echo $proxies[5]['port']; ?>">
	<button class="clear" data-clear="proxy5" type="button">X</button><br>

	<label for="proxy6Ip">6 adres serwera proxy</label>
	<input id="proxy6Ip" type="text" name="proxy6Ip" placeholder="255.255.255.255" value="<?php if(!empty($proxies) && array_key_exists(6, $proxies)) echo $proxies[6]['ip']; ?>">
	port <input id="proxy6Port" type="text" name="proxy6Port" placeholder="8080" value="<?php if(!empty($proxies) && array_key_exists(6, $proxies)) echo $proxies[6]['port']; ?>">
	<button class="clear" data-clear="proxy6" type="button">X</button><br>

	<label for="proxy7Ip">7 adres serwera proxy</label>
	<input id="proxy7Ip" type="text" name="proxy7Ip" placeholder="255.255.255.255" value="<?php if(!empty($proxies) && array_key_exists(7, $proxies)) echo $proxies[7]['ip']; ?>">
	port <input id="proxy7Port" type="text" name="proxy7Port" placeholder="8080" value="<?php if(!empty($proxies) && array_key_exists(7, $proxies)) echo $proxies[7]['port']; ?>">
	<button class="clear" data-clear="proxy7" type="button">X</button><br>

	<label for="proxy8Ip">8 adres serwera proxy</label>
	<input id="proxy8Ip" type="text" name="proxy8Ip" placeholder="255.255.255.255" value="<?php if(!empty($proxies) && array_key_exists(8, $proxies)) echo $proxies[8]['ip']; ?>">
	port <input id="proxy8Port" type="text" name="proxy8Port" placeholder="8080" value="<?php if(!empty($proxies) && array_key_exists(8, $proxies)) echo $proxies[8]['port']; ?>">
	<button class="clear" data-clear="proxy8" type="button">X</button><br>

	<label for="proxy9Ip">9 adres serwera proxy</label>
	<input id="proxy9Ip" type="text" name="proxy9Ip" placeholder="255.255.255.255" value="<?php if(!empty($proxies) && array_key_exists(9, $proxies)) echo $proxies[9]['ip']; ?>">
	port <input id="proxy9Port" type="text" name="proxy9Port" placeholder="8080" value="<?php if(!empty($proxies) && array_key_exists(9, $proxies)) echo $proxies[9]['port']; ?>">
	<button class="clear" data-clear="proxy9" type="button">X</button><br>

	<label for="proxy10Ip">10 adres serwera proxy</label>
	<input id="proxy10Ip" type="text" name="proxy10Ip" placeholder="255.255.255.255" value="<?php if(!empty($proxies) && array_key_exists(10, $proxies)) echo $proxies[10]['ip']; ?>">
	port <input id="proxy10Port" type="text" name="proxy10Port" placeholder="8080" value="<?php if(!empty($proxies) && array_key_exists(10, $proxies)) echo $proxies[10]['port']; ?>">
	<button class="clear" data-clear="proxy10" type="button">X</button><br>

	<button id="submit">Zapisz</button>
</form>

<?php

include_once "../footer.php";