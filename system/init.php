<?php

session_start();

require_once("config/config.php");
require_once("thirdparty/twitter/twitteroauth.php");
require_once("class/twitteroauth.class.php");

//require_once("thirdparty/logs/Logger.php");
require_once("class/db.class.php");
require_once("class/stats.class.php");
require_once("class/user.class.php");
require_once("class/twitter.class.php");
require_once("class/chat.class.php");

//Logger::configure('system/config/log.config.xml');

//$log = Logger::getLogger('myLogger');
$db = new DB();
$stats = new Stats();

if(isset($_SESSION['oauth_token']) && isset($_SESSION['oauth_token_secret'])){
	$twitter = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
}elseif(UserTwitter::isAuthed()){
	$twitter = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $_SESSION['access_token']['oauth_token'], $_SESSION['access_token']['oauth_token_secret']);
}else{
	$twitter = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
}

if(isset($_SESSION['oauth_token']) && isset($_SESSION['oauth_token_secret'])){
	$twitUser = new UserTwitter(CONSUMER_KEY, CONSUMER_SECRET, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
}elseif(UserTwitter::isAuthed()){
	$twitUser = new UserTwitter(CONSUMER_KEY, CONSUMER_SECRET, $_SESSION['access_token']['oauth_token'], $_SESSION['access_token']['oauth_token_secret']);
}else{
	$twitUser = new UserTwitter(CONSUMER_KEY, CONSUMER_SECRET);
}

$tweeter = new TwitTwitter();
$user = new User();

?>