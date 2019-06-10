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
		$this->_dbHost = $dbHost;
		$this->_dbName = $dbName;
		$this->_dbUser = $dbUser;
		$this->_dbPassword = $dbPassword;
	}

	public static function close()
	{
		self::$_db = null;
	}
	
	public static function getContext()
	{
		if(self::$_db === null)
			return null;
		return self::$_db;
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

	public function setContext()
	{
		if(self::$_db === null)
			self::$_db = new PDO("mysql:host=".$this->_dbHost.";dbname=".$this->_dbName, $this->_dbUser, $this->_dbPassword);
		else
			throw new Exception("Only one instance allowed.");
	}

	public static function setContextFromFile($iniConfigPath)
	{
		if(self::$_db === null)
		{
			$dbConfig = self::getDbIni($iniConfigPath);
			self::$_db = new PDO("mysql:host={$dbConfig['server']};dbname={$dbConfig['database']}", $dbConfig['user'], $dbConfig['password']);
		}
		else
			throw new Exception("Only one instance allowed.");
	}

	public static function query($preparedQuery, $executeArray = null, $returnOption = null)
	{
		$context = self::$_db;
		if($context)
		{
			$context->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$stmt = $context->prepare($preparedQuery);
			if($executeArray)
				$stmt->execute($executeArray);
			else
				$stmt->execute();
				
			if($returnOption)
			{
				switch($returnOption)
				{
					case "fetch":
						return $stmt->fetch(PDO::FETCH_ASSOC);
					case "fetchAll":
						return $stmt->fetchAll(PDO::FETCH_ASSOC);
					case "fetchColumn":
						return $stmt->fetchColumn();
					default:
						throw new Exception("Invalid return option.");
				}
			}
		}
		else
			throw new Exception("Invalid PDO object.");
	}
}