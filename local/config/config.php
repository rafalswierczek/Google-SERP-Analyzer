<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Page Title</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<script src="../scripts/checkDatabaseConnection.js"></script>
</head>
<body>

<?php
	$currentUrl = explode("?", $_SERVER['REQUEST_URI'])[0];
?>

<h1>Konfiguracja aplikacji</h1>
<p>Połączenie z lokalną bazą danych</p>

<form id="formDbConfig" action="<?php echo htmlspecialchars($currentUrl); ?>" method="POST">
	<label for="dbServer">Adres serwera bazy danych</label>
	<input id="dbServer" type="text" name="dbServer" required value="localhost"><br>

	<label for="dbName">Nazwa bazy danych</label>
	<input id="dbName" type="text" name="dbName" required value="google_serp_analyzer"><br>

	<label for="dbUser">Użytkownik bazy danych</label>
	<input id="dbUser" type="text" name="dbUser" required value="root"><br>

	<label for="dbPassword">Hasło użytkownika bazy danych</label>
	<input id="dbPassword" type="password" name="dbPassword" required value="8462284622"><br>

	<button id"submit">Zapisz</button>
</form>

<?php

include_once "../footer.php";