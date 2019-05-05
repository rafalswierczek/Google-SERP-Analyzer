<?php

class Db
{
	private static $_db;
	private $_dbHost;
	private $_dbName;
	private $_dbUser;
	private $_dbPassword;
	
	public function __construct($dbHost, $dbName, $dbUser, $dbPassword)
	{
		$this->_dbHost = htmlspecialchars($dbHost);
		$this->_dbName = htmlspecialchars($dbName);
		$this->_dbUser = htmlspecialchars($dbUser);
		$this->_dbPassword = htmlspecialchars($dbPassword);
	}

	public function getContext()
	{
		try
		{
			if(self::$_db === null)
			{
				self::$_db = new PDO("mysql:host=".$this->_dbHost.";dbname=".$this->_dbName, $this->_dbUser, $this->_dbPassword);
				return self::$_db;
			}
			
			return null;
		}
		catch (PDOException $e)
		{
			echo "PDO error: ". $e->getMessage() ."<br>";
		}
	}
	
	public static function getDbIni($path)
	{
		if(!file_exists($path))
			throw new Exception("Plik `db.ini` nie istnieje");

		$dbConfig = parse_ini_file($path);
		if(!empty($dbConfig['server']) && !empty($dbConfig['database']) && !empty($dbConfig['user']) && !empty($dbConfig['password']))
			return $dbConfig;
		else
			throw new Exception("Plik `db.ini` posiada złą zawartość.");
	}

	public static function getContextFromFile($iniConfigPath)
	{
		try
		{
			if(self::$_db === null)
			{
				try
				{
					$dbConfig = self::getDbIni($iniConfigPath);
				}
				catch(Exception $e)
				{
					die($e->getMessage());
				}

				self::$_db = new PDO("mysql:host={$dbConfig['server']};dbname={$dbConfig['database']}", $dbConfig['user'], $dbConfig['password']);
				return self::$_db;
			}

			return null;
		}
		catch (PDOException $e)
		{
			die("PDO error: ". $e->getMessage() ."<br>");
		}
	}

	public static function query($query, $returnOption = null)
	{
		try
		{
			$context = self::$_db;
			$context->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$stmt = $context->prepare($query);
			$stmt->execute();

			if($returnOption)
			{
				switch($returnOption)
				{
					case "fetchAll":
						return $stmt->fetchAll(PDO::FETCH_ASSOC);
					case "fetchColumn":
						return $stmt->fetchColumn();
				}
			}

			return true;
		}
		catch(PDOException $e)
		{
			die("PDO error: ". $e->getMessage() ."<br>");
		}
	}
}