<?php

class Chat {
    
    public static function create_event($creator, $topic, $description, $start_time, $end_time, $questions) {
        global $db;
        $questions = json_encode($questions);
        $db->add('ritechat_chats.chat', array('created_by'=>$creator,'time_start'=>$start_time,'time_end'=>$end_time,'topic'=>$topic,'questions'=>$questions));
    }
    
    public static function chat_exists($chat) {
        global $db;
        $check = $db->query("SELECT chatid,hashtag FROM ritechat_chats.chat WHERE chatid='{$chat}' OR hashtag='{$chat}'");
        if($check->rowCount() != 0){
            return true;
        }else{
            return false;
        }
    }
    
    public static function get_chatid($chat) {
        global $db;
        $chat = $db->query("SELECT chatid,hashtag FROM ritechat_chats.chat WHERE hashtag='{$chat}'");
        $chat_info = $chat->fetch(PDO::FETCH_ASSOC);
        return $chat_info['chatid'];
    }
    
    public static function get_event_info_json($chat) {
        global $db;
        $event = $db->query("SELECT * FROM ritechat_chats.chat_event WHERE chatid='{$chat}'");
        $event_arr = $event->fetch(PDO::FETCH_ASSOC);
        $event_json = json_encode($event_arr);
        return $event_json;
    }
    
    public static function is_event_now($chatid) {
        global $db;
        
        $event = self::get_event_info_json($chatid);
        $event_arr = json_decode($event, true);
        $current_time = time();
        
        //echo "<pre>  |  Current Time: {$current_time}\n  |  Start Time: {$event_arr['time_start']}\n  |  End Time: {$event_arr['time_end']}\n</pre>";
        if($current_time > $event_arr['time_start'] && $current_time < $event_arr['time_end']){
            return true;
        }else{
            return false;
        }
    }
    
    public static function get_chat_info($chat) {
        global $db;
        if(self::chat_exists($chat)){
            $chat = $db->query("SELECT * FROM ritechat_chats.chat WHERE chatid='{$chat}' OR hashtag='{$chat}'");
            $chat_info = $chat->fetch(PDO::FETCH_ASSOC);
            return $chat_info;
        }else{
            return false;
        }
        
    }
    
    public static function get_event_questions($chat) {
        $chatInfo = self::get_event_info_json($chat);
        $questions = json_decode($chatInfo, true);
        
        $real_questions = explode(',', $questions['questions']);
        return $real_questions;
    }
    
    public static function event_questions_list($chat) {
        $questions = self::get_event_questions($chat);
        foreach($questions as $key=>$question) {
            echo "<li class='Q{$key}'>{$question}</li>";
        }
    }
    
}

?>