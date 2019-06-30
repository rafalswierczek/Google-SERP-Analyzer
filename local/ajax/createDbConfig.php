<?php

header('Content-type: application/json; charset=utf-8');

require_once "../libs/jsonMessage.php";
if(!empty($_POST['dbServer']) && !empty($_POST['dbName']) && !empty($_POST['dbUser']))
{
	require_once "../libs/db.php";
	
	try
	{
		if(!empty($_POST['dbPassword']))
			$db = new Db($_POST['dbServer'], $_POST['dbName'], $_POST['dbUser'], $_POST['dbPassword']);

		else
			$db = new Db($_POST['dbServer'], $_POST['dbName'], $_POST['dbUser']);
			
		$db->setContext();
		$dbExists = Db::query("SELECT COUNT(*) FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = ?", [$_POST['dbName']], "fetchColumn");

		if($dbExists)
		{
			try
			{
				$iniData = <<<INI
[database]
server = {$_POST['dbServer']}
database = {$_POST['dbName']}
user = {$_POST['dbUser']}
password = {$_POST['dbPassword']}
INI;

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
	`analysis_id` TINYINT UNSIGNED NOT NULL,
	`position` TINYINT UNSIGNED NOT NULL, 
	`date` DATE NOT NULL, 
	`time` TIME NOT NULL, 
	FOREIGN KEY (`analysis_id`) REFERENCES analyses(`analysis_id`), 
	PRIMARY KEY (`data_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;
QUERY;
			$dataStorageQuery = Db::query($query);
			Db::close();

			echo jsonMessage("result", "Pomyślnie skonfigurowano połączenie z bazą danych.");
		}
		else
			echo jsonMessage("error", "Baza danych '{$_POST['dbName']}' nie istnieje.");
	}
	catch(PDOException $e)
	{
		echo jsonMessage("error", $e->getMessage());
	}
}
else
	echo jsonMessage("error", "Nie można nawiązać połączenia z bazą danych. Niewystarczająca ilość danych z formularza.");