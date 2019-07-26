<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Konfiguracja aplikacji - DB</title>
	<meta name="description" content="Opis strony...">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="../../styles/config.css">
	<script src="../../scripts/createDbConfig.js"></script>
</head>
<body>
<a href="../index/index.php">Strona główna</a><br>

<?php

	require_once "../../libs/db.php";

	try
	{
		$dbIni= Db::getDbIni("../../db.ini");
	}
	catch(Exception $e)
	{
		//echo $e->getMessage();
	}
?>

<h1 class="header">Konfiguracja aplikacji</h1>
<p class="description">Połączenie z lokalną bazą danych</p>

<form id="formDbConfig" action="" method="POST">
	<label for="dbServer">Adres serwera bazy danych</label>
	<input id="dbServer" type="text" name="dbServer" value="<?php if(isset($dbIni)){echo $dbIni["server"];} ?>" required><br>

	<label for="dbName">Nazwa bazy danych</label>
	<input id="dbName" type="text" name="dbName" value="<?php if(isset($dbIni)){echo $dbIni["database"];} ?>" required><br>

	<label for="dbUser">Użytkownik bazy danych</label>
	<input id="dbUser" type="text" name="dbUser" value="<?php if(isset($dbIni)){echo $dbIni["user"];} ?>" required><br>

	<label for="dbPassword">Hasło użytkownika bazy danych</label>
	<input id="dbPassword" type="password" name="dbPassword" autocomplete="new-password" value="<?php if(isset($dbIni)){echo $dbIni["password"];} ?>"><br>

	<button id="submit">Zapisz</button>
</form>

<?php

include_once "../index/footer.php";