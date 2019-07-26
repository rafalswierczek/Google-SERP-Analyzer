<?php

if(!file_exists("../../db.ini"))
{
	header("location: config.php");
}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Konfiguracja aplikacji - Proxy</title>
	<meta name="description" content="Opis strony...">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="../../styles/addProxy.css">
	<script src="../../scripts/addProxiesToDb.js"></script>
</head>
<body>
<a href="../index/index.php">Strona główna</a><br>

<h1 class="header">Konfiguracja aplikacji</h1>
<p class="description">Dodanie adresów serwerów proxy</p>

<div id="proxies">
	<?php require_once "loadProxies.php"; ?>
</div>

<?php
require_once "../index/footer.php";