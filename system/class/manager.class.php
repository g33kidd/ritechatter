<?php
	
class manager{

	public static function createchat($chatname, $chatdesc, $userid){
		global $db;
		$db->add('ritechat_chats.chat', array('owner'=>'{$userid}','hashtag'=>'{$chatname}'));
	}
	
	public static function removechat($chatid, $owner){
		global $db;
		$db->query("REMOVE FROM ritechat_chats.chat WHERE chatid='{$chatid}' AND owner='{$owner}'");
	}
    
    public static function editchat($chatid, $data) {
        // edit chat from array
    }
    
}
	