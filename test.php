<?php

require_once("system/init.php");

$current_time = date('Y-m-d H:i:s');
$tweet = $db->query("SELECT * FROM ritechat_tweets.tweet WHERE schedule_time='{$current_time}'");

if($tweet->rowCount() > 0){

	$tweets = $tweet->fetchAll(PDO::FETCH_ASSOC);

	foreach($tweets as $tweet){
		$user = $db->query("SELECT * FROM ritechat_users.users WHERE userid='{$tweet['userid']}'");
		if($tweet->rowCount() > 0){
		
			$user = $user->fetch(PDO::FETCH_ASSOC);
			
			$user_token = $db->query("SELECT * FROM ritechat_token.twitter_tokens WHERE screen_name='{$user['username']}'");
			$user_token = $user_token->fetch(PDO::FETCH_ASSOC);
			
			echo $tweet['content'];
			
			$twitter = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $user_token['access_key'], $user_token['access_key_security']);
			//$twitter->post("statuses/update", array("status"=>$tweet['content']));
			
			if($twitter->http_code == (400 || 429)){ $log->info("Scheduled Tweet Not Posted."); }
			
		}else{
			
		}
	}
}

?>  