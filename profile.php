<?php

require_once("system/init.php");

if(isset($_GET['manager'])) {

try {
    global $db;
		
	echo "<pre>";
	print_r($profile_data);
	echo "</pre>";
}
catch(PDOException $e) {
    die("{$e}");
}

	
	
}
else {
	header("Location: ./index.php");
}


?>