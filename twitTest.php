<?php

require_once("system/init.php");

echo "<pre>";
$tweets = $twitter->post('statuses/update', array('status'=>'Some test text for #ritechatter….'));
print_r($tweets);
echo "</pre>";

?>