<?php

if(isset($_POST['dbServer']) && isset($_POST['dbName']) && isset($_POST['dbUser']) && isset($_POST['dbPassword']))
{
	require_once "../db.php";

	$db = new Db($_POST['dbServer'], 'INFORMATION_SCHEMA', $_POST['dbUser'], $_POST['dbPassword']);
	$context = $db->getContext();

	$stmt = $context->prepare("SELECT COUNT(*) FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '{$_POST['dbName']}'");
	$stmt->execute();
	$dbExists = $stmt->fetchColumn();

	if($dbExists)
	{
		$dbServer = htmlspecialchars($_POST['dbServer']);
		$dbName = htmlspecialchars($_POST['dbName']);
		$dbUser = htmlspecialchars($_POST['dbUser']);
		$dbPassword = htmlspecialchars($_POST['dbPassword']);
		$iniData = <<<INI
[database]
server = $dbServer
database = $dbName
user = $dbUser
password = $dbPassword
INI;
		$handle = fopen("../db.ini", "w");
		fwrite($handle, $iniData);
		fclose($handle);

		echo "true";
	}
}