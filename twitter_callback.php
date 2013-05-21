<?php

require_once("system/init.php");

if(isset($_REQUEST['oauth_token']) && $_SESSION['oauth_token'] !== $_REQUEST['oauth_token']) {
	$_SESSION['oauth_status'] = 'oldtoken';
	session_destroy();
	echo "Twitter fucked up!";
}

$access_token = $twitter->getAccessToken($_REQUEST['oauth_verifier']);

$_SESSION['access_token'] = $access_token;

unset($_SESSION['oauth_token']);
unset($_SESSION['oauth_token_secret']);

if(200 == $twitter->http_code) {
	global $db;
	$_SESSION['status'] = 'verified';
	$userdata = $twitter->get('account/verify_credentials');
	$db->add('ritechat_token.twitter_tokens', array('access_key'=>$access_token['oauth_token'],'access_key_security'=>$access_token['oauth_token_secret'],'screen_name'=>$userdata['screen_name']));
	
	if(isset($_SESSION['chat_no_auth_page'])){ header("Location: ".$_SESSION['chat_no_auth_page']); }else{ header("Location: /search/"); }
}else{
	$twitUser->endSession();
	header("Location: index.php");
}

?>