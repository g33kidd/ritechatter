<?php

require_once('../system/init.php');

$uri = $_GET['request'];
$uri = explode('/',$uri);

$method = $uri[0];
$function = $uri[1];
$variable = $uri[2];
$variable2 = $uri[3];

$response = "";

if(empty($function))
    die("No request function defined");

$current_request = "{$method}/{$function}/:{$variable}";
$available_request_types = array(
    'get/is_chat/:id',
    'get/chatid/:chat',
    'post/tweet/'
);

if($method == "get") {
    switch($function){
        //some basic chat functions
        case 'is_chat':
            if($chat->chat_exists($variable)){
                $response = json_encode(array('request'=>$current_request, 'msg'=>true));
            }else{
                $response = json_encode(array('request'=>$current_request, 'msg'=>false));
            }
            break;
        case 'chatid':
            if($chat->chat_exists($variable)){
                $chatid = $chat->get_chatid($variable);
                $response = json_encode(array('request'=>$current_request, 'msg'=>$chatid));
            }else{
                $response = json_encode(array('request'=>$current_request, 'msg'=>false));
            }
        case 'event_now':
            if($chat->chat_exists($variable)){
                $chatid = $chat->get_chatid($variable);
                if($chat->is_event_now($chatid)){
                    $response = json_encode(array('request'=>$current_request, 'msg'=>true));
                }else{
                    $response = json_encode(array('request'=>$current_request, 'msg'=>false));
                }
            }else{
                $response = json_encode(array('request'=>$current_request, 'msg'=>false));
            }
            break;
		case 'tweets':
			$tweets = $tweeter->pullTweets($variable2, 15, $variable);
			$response = json_encode($tweets);
			break;
		case 'isAuthed':
			if($twitUser->isAuthed()){
				$response = json_encode(array('request'=>$current_request, 'msg'=>true));
			}else{
				$response = json_encode(array('request'=>$current_request, 'msg'=>false));
			}
			break;
    }
}elseif($method == "post"){
    
    switch($function){
        case 'tweet':
        	if($twitUser->isAuthed()){
        		$tweet_post = $tweeter->tweet($_POST['status']);
        		//$tweet_post = $twitter->post('statuses/update', array('status'=>$_POST['status']));
        		if($twitter->http_code == (200)) { $response = json_encode($tweet_post); }elseif($twitter->http_code == (400 || 429)){
        			$response = json_encode(array('request'=>$current_request, 'msg'=>'Error: '. $twitter->http_code));
        		}
        	}else{
        		$response = json_encode(array('request'=>$current_request, 'msg'=>'User not authed into twitter.'));
        	}
	        break;
	        
    }
    
}


header('Content-type: application/json');
if(empty($response)){
    echo "No Response Available";
}else{
    echo $response;
}

?>