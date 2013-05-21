<?php

         /*
         * If you modify this please change the one on the other servers as well
         * 
         * Version stamp: 1 (add one every change)
         */


	//Function to sanitize values received from the form. Prevents SQL injection
	function clean($str,$full = false,$spaces = false,$lower = false) {
	
		if(!$full){
			$str = @trim($str);
			if(get_magic_quotes_gpc()) {
				$str = stripslashes($str);
			}
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

require_once("system/init.php");
$twitter = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $_SESSION['access_token']['oauth_token'], $_SESSION['access_token']['oauth_token_secret']);

 $text = clean("Quotes are not wanting to work? I'm frustrated. #g33kiddchat");
 
 //$text = preg_replace('/http:\/\/([a-z0-9_.\/]+)/i', '<a href="http://$1"     target="_blank">http://$1</a>', $text);
 $tweet = $twitter->post('statuses/update', array("status"=>"".$text.""));
    
 if($twitter->http_code == 200){
 	return $tweet;
 }

?>