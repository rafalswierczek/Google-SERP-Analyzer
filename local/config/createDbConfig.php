<?php

if(isset($_POST['dbServer']) && isset($_POST['dbName']) && isset($_POST['dbUser']) && isset($_POST['dbPassword']))
{
	require_once "../db.php";

	$dbServer = htmlspecialchars($_POST['dbServer']);
	$dbName = htmlspecialchars($_POST['dbName']);
	$dbUser = htmlspecialchars($_POST['dbUser']);
	$dbPassword = htmlspecialchars($_POST['dbPassword']);

	try
	{
		$db = new Db($dbServer, $dbName, $dbUser, $dbPassword);
		$context = $db->getContext();
		if(!$context)
			die("Only 1 database instance allowed");
		$dbExists = Db::query("SELECT COUNT(*) FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '$dbName'", "fetchColumn");

		if($dbExists)
		{
			$iniData = <<<INI
[database]
server = $dbServer
database = $dbName
user = $dbUser
password = $dbPassword
INI;
			try
			{
				$handle = fopen("../db.ini", "w");
				fwrite($handle, $iniData);
				fclose($handle);
			}
			catch(Exception $e)
			{
				die("Nie można było utworzyć pliku `db.ini`: ".$e->getMessage());
			}

			$proxiesQuery = Db::query("CREATE TABLE IF NOT EXISTS `proxies` (`proxy_id` TINYINT UNSIGNED NOT NULL, `ip` VARCHAR(15) NOT NULL, `port` VARCHAR(30) NOT NULL, PRIMARY KEY (`proxy_id`, `ip`))");
			if(!$proxiesQuery)
			die("Nie można było utworzyć tabeli `proxies`");

			$analysesQuery = Db::query("CREATE TABLE IF NOT EXISTS `analyses` (`analysis_id` TINYINT UNSIGNED NOT NULL, `domain` VARCHAR(75) NOT NULL, `phrase` VARCHAR(200) NOT NULL, `proxy_id` TINYINT UNSIGNED NOT NULL, FOREIGN KEY (`proxy_id`) REFERENCES proxies(`proxy_id`), PRIMARY KEY (`analysis_id`))");
			if(!$analysesQuery)
				die("Nie można było utworzyć tabeli `analyses`");

			$dataStorageQuery = Db::query("CREATE TABLE IF NOT EXISTS `dataStorage` (`key` VARCHAR(50) NOT NULL, `value` VARCHAR(100) NOT NULL, PRIMARY KEY (`key`))"); // simple data storage // trzyma najmniejszy wolny analysis_id
			if(!$dataStorageQuery)
				die("Nie można było utworzyć tabeli `dataStorage`");
			
			die("true");
		}
		else
			die("Baza danych '$dbName' nie istnieje.");
	}
	catch(PDOException $e)
	{
		die($e->getMessage());
	}
}