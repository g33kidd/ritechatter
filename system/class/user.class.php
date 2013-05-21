<?php

class User {
    
    public static function genSalt() {
    	$seed = '';
    	for($i = 0; $i < 16; $i++){
    		$seed .= chr(mt_rand(0, 255));
    	}
    	$salt = substr(strtr(base64_encode($seed),'+', '.'), 0, 22);
    	return $salt;
    }
    
    public static function genHash($password) {
    	$hash = crypt($password, '$2y$12' . '$' . self::genSalt());
    	return $hash;
    }
    
    public function login($username, $password) {
    	global $db;
    	$user = $db->query("SELECT * FROM ritechat_users.user WHERE username='{$username}'");
    	if($user->rowCount() !== 0){
    		$user = $db->query(PDO::FETCH_ASSOC);
    		$access = self::getAccessToken($user['username']);
    		
    	}else{
    		
    	}
    }
    
    public function getAccessToken($username){
    	global $db;
    	$tokens = $db->query("SELECT access_key,access_key_security,screen_name FROM ritechat_token.twitter_tokens WHERE screen_name='{$username}'");
    	if($tokens->rowCount !== 0){
    		$accessToken = $tokens->fetch(PDO::FETCH_ASSOC);
			$access = array('access_key'=>$accessToken['access_key'],'access_key_secret'=>$accessToken['access_key_security']);
			return $access;
    	}else{
    		return false;
    	}
    }
    
    public function userdata($user){
    	global $db;
    	$query = $db->query("SELECT ritechat_users.users_profile.* FROM ritechat_users.users_profile INNER JOIN ritechat_users.users ON users_profile.userid = users.userid WHERE users.username = '{$user}'");
    	$user_data = $query->fetch(PDO::FETCH_ASSOC);
    	return $user_data;
    }
    
    public function register($data) {
        global $db;
        if(!is_array($data) || !count($data)) { return false; }
        $check = $db->query("SELECT * FROM ritechat_users.users WHERE email='{$data['email']}' OR username='{$data['username']}'");
        if($check->rowCount() == 0){
        	$add_user = $db->add('ritechat_users.users', $data);
        	if($add_user){
        		return true;
        	}else{
        		return false;
        	}
        }else{
        	return false;
        }
    }
    
    public function is_loggedin(){
        if(empty($_SESSION['access_token']) || empty($_SESSION['access_token']['oauth_token']) || empty($_SESSION['access_token']['oauth_token_secret']) || empty($_SESSION['uid'])){
            return false;    
        }else{
            return true;
        }
    }
    
}

class UserTwitter extends TwitterOAuth {
	
	public $authState;
	
	public static function endSession() {
		session_destroy();
		if(!self::isAuthed()){
			return true;
		}else{
			return false;
		}
	}
	
	public static function isAuthed() {
	    if(empty($_SESSION['access_token']) || empty($_SESSION['access_token']['oauth_token']) || empty($_SESSION['access_token']['oauth_token_secret'])) {
			return false;
		}else{
			return true;
		}
    }
	
	public static function redirectAuthedUser() {
		
	}
	
}

?>