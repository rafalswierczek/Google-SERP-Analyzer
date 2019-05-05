<?php

if(!file_exists("db.ini"))
{
	header("location: config/config.php");
}

require_once("header.php");
?>

<h3>Index, wykres, wybór analizy, ...</h3>

<?php
require_once("footer.php");
?>