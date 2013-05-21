<?php

class TwitTwitter {

    public $userdata;
    
    public static function pullTweets($tweet_count=15, $count=5, $term){
    	global $db;
    	$tokens = $db->query("SELECT * FROM ritechat_token.twitter_tokens LIMIT 15");
		$count = $tokens->rowCount();

		if($tokens->rowCount() > 0){
	
			$token = $tokens->fetchAll(PDO::FETCH_ASSOC);
			$rand = rand(2,$count) - 1;
			$twitAuth = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $token[$rand]['access_key'], $token[$rand]['access_key_security']);
			$check = $twitAuth->get('account/verify_credentials');
			while($twitAuth->http_code == (429 || 400)){
				$rand = $rand++;
				$twitAuth = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $token[$rand]['access_key'], $token[$rand]['access_key_security']);
			}
			
			$tweets = $twitAuth->get('search/tweets', array("q"=>"%23{$term}",'count'=>$tweet_count));
			if($twitAuth->http_code==200){
				return $tweets;
			}else{
				return false;
			}
		}
    }
    
    public static function tweet($tweet_text) {
    	global $db;
    	
    	$twitter = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $_SESSION['access_token']['oauth_token'], $_SESSION['access_token']['oauth_token_secret']);
    	$tweet = $twitter->post('statuses/update', array("status"=>"".self::clean($tweet_text).""));
    	
    	if($twitter->http_code == 200){
    		return $tweet;
    	}
    }
    
    public static function userData() {
    	$twitter = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $_SESSION['access_token']['oauth_token'], $_SESSION['access_token']['oauth_token_secret']);
    	$user = $twitter->get('account/verify_credentials');
    	return $user;
    }
    
    public static function scheduleTweet($data, $time) {
    	global $db;
    	//$db->add("");
    }
    
    // THIS FUNCTION!!!
    public static function clean($str,$full = false,$spaces = false,$lower = false) {
	
		if(!$full){
			$str = @trim($str);
			$str = stripslashes($str);
			
			return $str;
		}else{
			//strip special characters
			$find = array("\"","\'","\\","|","%","(",")","<",">","#","{","}","^","~","[","]","`","&","*","$","@","!",",",".","+",";",":","?","=","/");
			$str = str_replace($find,"",$str);
			//Strip spaces on left and right
			$str = trim($str," -_");
			if($spaces){
				//replace spaces with dash
				$str = str_replace(" ","-",$str);
			}
			//Make all lowercase
			if($lower){
				$str = strtolower($str);
			}
			return $str;
		}
		
	}
    // THIS FUNCTION!!!
    
}

?>