<?php

if(!file_exists("db.ini"))
{
	header("location: config/config.php");
}

require_once("header.php");
?>



<?php
require_once("footer.php");
?>
