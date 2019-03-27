<?php

class Db
{
	private static $_db;
	
	protected function __construct()
	{
	}

	public static function getContext()
	{
		try
		{
			$dbConfig = parse_ini_file('/home/wariar/domains/gsaproject.pl/db.ini');
			if(static::$_db === null)
			{
				static::$_db = new PDO("mysql:host=localhost;dbname={$dbConfig['database']}", $dbConfig['user'], $dbConfig['password']);
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

var_dump(Db::getContext());