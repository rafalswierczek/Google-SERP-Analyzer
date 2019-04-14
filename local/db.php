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
			if(static::$_db === null)
			{
				static::$_db = new PDO("mysql:host=".$this->_dbHost.";dbname=".$this->_dbName, $this->_dbUser, $this->_dbPassword);
				return static::$_db;
			}
			
			return null;
		}
		catch (PDOException $e)
		{
			echo "PDO error: ". $e->getMessage() ."<br>";
			return null;
		}
	}

	public static function getContextFromFile($iniConfigPath)
	{
		try
		{
			if(static::$_db === null)
			{
				$dbConfig = parse_ini_file($iniConfigPath);
				static::$_db = new PDO("mysql:host={$dbConfig['server']};dbname={$dbConfig['database']}", $dbConfig['user'], $dbConfig['password']);
				return static::$_db;
			}

			return null;
		}
		catch (PDOException $e)
		{
			echo "PDO error: ". $e->getMessage() ."<br>";
			return null;
		}
	}
}