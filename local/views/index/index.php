<?php

if(!file_exists("../../db.ini"))
{
	header("location: ../config/config.php");
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
	<title>Strona główna - Google SERP Analyzer</title>
	<meta name="description" content="Google Search Engine Results Page Analyzer zapewnia graficzną prezentację pozycji wyników organicznych danych domen w czasie">
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!--<meta http-equiv="X-UA-Compatible" content="ie=edge">-->
	<link rel="shortcut icon" type="image/x-icon" sizes="16x16" href="/favicon.ico">
	<link rel="stylesheet" href="../../styles/index.css">
</head>
<body>
<h1> Logo / header </h1>

<div id="addModal">
	<div id="addWindow">
		<form action="" method="POST" id="addAnalysisForm">
			<h2>Dodaj analizę</h2>

			<div id=addWindowBoxContainer>
				<div class="addWindowBox">
					<p class="addWindowText">Wprowadź domenę, która wraz z frazą będą służyć do określenia pozycji tej domeny w czasie dla wyników organicznych Google.</p>
					<input type="text" name="domain" placeholder="domena" class="textInput">
				</div>
				<div class="addWindowBox">
					<p class="addWindowText">Wprowadź frazę lub słowo kluczowe, dla którego Google zbuduje listę domen wyników organicznych (SERP), które posłużą do stworzenia wykresu.</p>
					<input type="text" name="phrase" placeholder="fraza" class="textInput">
				</div>
				<div class="addWindowBox">
					<p class="addWindowText">Wybierz język, dla którego Google zwróci wyniki organiczne. Dla słowa kluczowego (na przykład) \"pizza\" wyniki będą się różnić w zależności od języka.</p>
					<input type="radio" class="regionInput" name="region" value="pl">
					<img class="langFlag" src="poland.svg" alt="polish language">
					<input type="radio" class="regionInput" name="region" value="en">
					<img class="langFlag" src="england.svg" alt="engish language">
				</div>
			</div>

			<button id="saveAnalysis">Zapisz</button>
		</form>
	</div>
</div>

<div id="infoBox">
	<div id="domainInfo"></div>
	<div id="phraseInfo"></div>
</div>

<a href="../config/addProxy.php">Zarządzaj serwerami proxy</a>

<div id="analysesBox">
	<h2>Analizy</h2>
	<div id="analyses">
		<!-- automatyczne dodawanie -->
	</div>

	<button id="addAnalysis">Dodaj analizę</button>
</div>

<div id="chart">

</div>

<script src="../../scripts/analysis.js"></script>

<?php
require_once("footer.php");
?>