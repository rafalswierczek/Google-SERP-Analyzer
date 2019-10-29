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

	<script>console.log("%cGSA - "+"%cGoogle Search Engine Resolution Page Analyzer", "color: #2A4C62; font-size: 26px;", "color: #3F7598; font-size: 20px;");</script>
    <script src="../../libs/zoomcharts/zoomcharts.js"></script>
    <script>
        var ZoomChartsLicense = "ZCS-1e5u2p68g: ZoomCharts SDK Single Developer Licencefor raf..@..ai.pl (valid for development only); upgrades until: 2019-08-22";
        var ZoomChartsLicenseKey = "2d12ffb90ef850dffdf4264b3e0ba559a6f3b0a6d9eec9fd1f"+
        "a15d4239abecefcfe2f2edb05bcf66b23b1e8cd70543569eeea62e50500bc9ea0ee766500c5ef"+
        "4aa9fd9debefefa56ddba9a1933733722f427fe5289b14b71f76d5aa1831029abfb18c4fef60f"+
        "3237f1547c6efa03dde4cc7d7b767ef48413f9876caad8e0efba10cb5910f92bed202d6218d5e"+
        "2a518b54d183929a46054a65b9183ee2b938b4abe2eb6caba736b741c483ef0cbced194f1df0f"+
        "42ab3500d5b6dbc179395230e0549d8c5baeb029dc0b4b05dbe46ab3ee0acd0b07b5b6735433a"+
        "24bdce6a4e1c6ddc5c5ac3471fd2c9c1d0b738379c203f5bc7f75fcd9e50272fe2462d073805b";
    </script>
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