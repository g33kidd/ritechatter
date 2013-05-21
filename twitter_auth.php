<?php

require_once("system/init.php");

$request_token = $twitter->getRequestToken("http://ritechatter.com/twitter_callback.php");
$_SESSION['oauth_token'] = $token = $request_token['oauth_token'];
$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
switch($twitter->http_code){
	case 200:
		$url = $twitter->getAuthorizeURL($token);
		header('Location: '. $url);
		break;
	default:
		return false;
}

?>