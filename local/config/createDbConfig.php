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

			$proxiesQuery = Db::query("CREATE TABLE IF NOT EXISTS `proxies` (`id` TINYINT UNSIGNED NOT NULL, `ip` VARCHAR(15) NOT NULL, `port` VARCHAR(30) NOT NULL, `analyses`,PRIMARY KEY (`id`, `ip`))");
			if(!$proxiesQuery)
			die("Nie można było utworzyć tabeli `proxies`");

			$analysesQuery = Db::query("CREATE TABLE IF NOT EXISTS `analyses` (`id` TINYINT UNSIGNED NOT NULL, `domain` VARCHAR(75) NOT NULL, `phrase` VARCHAR(200) NOT NULL, PRIMARY KEY (`id`))");
			if(!$analysesQuery)
				die("Nie można było utworzyć tabeli `analyses`");
			
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