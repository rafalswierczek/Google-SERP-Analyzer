<?php

header('Content-type: application/json; charset=utf-8');

require_once "../libs/jsonMessage.php";
if(!empty($_POST['dbServer']) && !empty($_POST['dbName']) && !empty($_POST['dbUser']) && !empty($_POST['dbPassword']))
{
	require_once "../libs/db.php";

	$dbServer = $_POST['dbServer'];
	$dbName = $_POST['dbName'];
	$dbUser = $_POST['dbUser'];
	$dbPassword = $_POST['dbPassword'];

	try
	{
		$db = new Db($dbServer, $dbName, $dbUser, $dbPassword);
		$db->setContext();
		$dbExists = Db::query("SELECT COUNT(*) FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = ?", [$dbName], "fetchColumn");

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
				echo "error: ". $e->getMessage();
			}

			$query = <<<QUERY
CREATE TABLE IF NOT EXISTS `proxies` (
	`proxy_id` TINYINT UNSIGNED NOT NULL, 
	`ip` VARCHAR(15) NULL, 
	`port` VARCHAR(30) NULL, 
	`user` VARCHAR(100) NULL, 
	`password` VARCHAR(100) NULL, 
	UNIQUE (`ip`), 
	PRIMARY KEY (`proxy_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;
QUERY;
			Db::query($query);
			Db::query("INSERT INTO `proxies` (proxy_id) VALUES (0) ON DUPLICATE KEY UPDATE proxy_id = 0");

			$query = <<<QUERY
CREATE TABLE IF NOT EXISTS `analyses` (
	`analysis_id` TINYINT UNSIGNED NOT NULL, 
	`domain` VARCHAR(75) NOT NULL, 
	`phrase` VARCHAR(200) NOT NULL, 
	`country_code` CHAR(2) NOT NULL, 
	`proxy_id` TINYINT UNSIGNED NOT NULL, 
	FOREIGN KEY (`proxy_id`) REFERENCES proxies(`proxy_id`), 
	PRIMARY KEY (`analysis_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;
QUERY;
			Db::query($query);

			$query = <<<QUERY
CREATE TABLE IF NOT EXISTS `dataStorage` (
	`data_id` INT UNSIGNED NOT NULL AUTO_INCREMENT, 
	`domain` VARCHAR(75) NOT NULL, 
	`phrase` VARCHAR(200) NOT NULL, 
	`position` TINYINT UNSIGNED NOT NULL, 
	`date` DATE NOT NULL, 
	`time` TIME NOT NULL, 
	PRIMARY KEY (`data_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;
QUERY;
			$dataStorageQuery = Db::query($query);
			Db::close();

			echo jsonMessage("result", "Pomyślnie skonfigurowano połączenie z bazą danych.");
		}
		else
			echo jsonMessage("error", "Baza danych '$dbName' nie istnieje.");
	}
	catch(PDOException $e)
	{
		echo jsonMessage("error", $e->getMessage());
	}
}
else
	echo jsonMessage("error", "Nie można nawiązać połączenia z bazą danych. Niewystarczająca ilość danych z formularza.");