<?php
require_once("system/init.php");

$chatInfo = $chat->get_chat_info($_GET['chat']);
$chatid = $chat->get_chatid($_GET['chat']);

if(!$twitUser->isAuthed()){
	$_SESSION['last_no_auth_page'] = $chatInfo['hashtag'];
}

?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<base href="/">
		<meta charset="UTF-8">
		<title>Ritechatter &mdash; the best twitter chat client!</title>
		<link rel="stylesheet" href="assets/css/bootstrap.min.css">
		<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css"/>
		<link rel="stylesheet" href="assets/css/font-awesome.min.css">
		<link rel="stylesheet" href="assets/css/bootstrap-responsive.min.css">
		<link rel="stylesheet" href="assets/css/chatter.css">

		<!-- Special Meta Tags will be here.... -->
		<!-- like really special meta stuff -->
	</head>
	<body>
		
		<div class="container main-page">
			<div class="row main-container">

				<div class="span2 left-section">
					<div class="nav">
						<div class="item"><a href="/authin/">Home</a></div>
						<div class="item">My Chats</div>
						<div class="item"><a href="/logout/">Logout</a></div>
					</div>
				</div>

				<div class="span6 middle-section box">
					<div class="tweets">
                        <div class="tweetbox">
                            <div class="padding"><textarea class="textarea"></textarea><button class="btn btn-primary">Send Tweet</button></div>
            			</div>
						<div class="padding">
                            <div id="tweets_feed">
                                
                                <script id="tweet_template" type="text/x-handlebars-template">
                                    <div class="tweet {{ from_user }} {{ highlight_type }}" id="tweet {{ tweet_id }}">
                						<div class="padding">
        									<div class="top">
        										<div class="profile-img"><img src="{{ profile_image_url }}" width="48" height="48"></div>
        										<div class="content">
        											<div class="twitter-tweet"><a href="http://twitter.com/{{ from_user }}" target="_new">@{{ from_user }}</a> &mdash; {{ tweet_text }}</div>
        										</div>
        									</div>
        									<div class="bottom action-btns" id="action-btns">
        										<div class="action-buttons">
        											<div class="pull-left">
        												<div class="action-btn act-btn" title="Retweet" data-retweet="{{ tweet_id }}"><i class="icon-retweet icon-large"></i></div>
        												<div class="action-btn act-btn" title="Favorite" data-favorite="{{ tweet_id }}"><i class="icon-star icon-large"></i></div>
        												<div class="action-btn act-btn" title="Reply-To" data-reply="{{ tweet_id }}"><i class="icon-reply icon-large"></i></div>
        												<div class="action-btn act-btn" title="Follow" data-follow="{{ from_user }}"><i class="icon-user icon-large"></i></div>
        												<div class="action-btn act-btn" title="Add to List" data-add-to-list="{{ from_user }}"><i class="icon-list icon-large"></i></div>
        											</div>
        											<div class="pull-right">
        												<div class="action-btn act-btn" title="Hide {{ from_user }}'s messages." data-block-user="{{ from_user }}"><i class="icon-eye-close icon-large"></i></div>
        												<div class="action-btn act-btn" title="Highlight {{ from_user }}'s messages." data-feature-user="{{ from_user }}"><i class="icon-lightbulb icon-large"></i></div>
        											</div>
        										</div>
        									</div>
        								</div>
        							</div>
                                </script>
                                <script id="bulk_tweet_template" type="text/x-handlebars-template">
                                    {{#each tweets}}
                                    <div class="tweet {{ from_user }} {{ highlight_type }}" id="tweet {{ tweet_id }}">
            							<div class="padding">
        									<div class="top">
        										<div class="profile-img"><img src="{{ profile_image_url }}" width="48" height="48"></div>
        										<div class="content">
        											<div class="twitter-tweet"><a href="http://twitter.com/{{ from_user }}" target="_new">@{{ from_user }}</a> &mdash; {{ tweet_text }}</div>
        										</div>
        									</div>
        									<div class="bottom action-btns" id="action-btns">
        										<div class="action-buttons">
        											<div class="pull-left">
        												<div class="action-btn act-btn" title="Retweet" data-retweet="{{ tweet_id }}"><i class="icon-retweet icon-large"></i></div>
        												<div class="action-btn act-btn" title="Favorite" data-favorite="{{ tweet_id }}"><i class="icon-star icon-large"></i></div>
        												<div class="action-btn act-btn" title="Reply-To" data-reply="{{ tweet_id }}"><i class="icon-reply icon-large"></i></div>
        												<div class="action-btn act-btn" title="Follow" data-follow="{{ from_user }}"><i class="icon-user icon-large"></i></div>
        												<div class="action-btn act-btn" title="Add to List" data-add-to-list="{{ from_user }}"><i class="icon-list icon-large"></i></div>
        											</div>
        											<div class="pull-right">
        												<div class="action-btn act-btn" title="Hide {{ from_user }}'s messages." data-block-user="{{ from_user }}"><i class="icon-eye-close icon-large"></i></div>
        												<div class="action-btn act-btn" title="Highlight {{ from_user }}'s messages." data-feature-user="{{ from_user }}"><i class="icon-lightbulb icon-large"></i></div>
        											</div>
        										</div>
        									</div>
        								</div>
        							</div>
                                    {{/each}}
                                </script>
                                
                            </div>
						</div>
					</div>
				</div>
				<div class="span4 right-section">
					<div class="chat-page-actions">
						<div class="chat-page-action-btn"><i class="icon-filter icon-large"></i></div>
						<div class="chat-page-action-btn"><i class="icon-comments icon-large"></i></div>
						<div class="chat-page-action-btn"><i class="icon-eye-open icon-large"></i></div>
						<div class="chat-page-action-btn"><i class="icon-cogs icon-large"></i></div>
						<div class="clear"></div>
					</div>
					<div class="chat-page-stats hide">
						<div class="stats-small">2500<span>views</span></div>
						<div class="stats-small">230<span>in chat now</span></div>
						<div class="clear"></div>
					</div>
					<div class="modules">
						<div class="padding">
							<div class="module hide topic" id="topic"><div class="padding">
								<div class="header">Topic</div>
								<div class="content"></div>
							</div></div>
							<div class="module hide description" id="description"><div class="padding">
								<div class="header">Description</div>
								<div class="content"></div>
							</div></div>
							<div class="module hide questions" id="questions"><div class="padding">
								<div class="header">Today's Questions</div>
								<ol class="question-list content">
                                    
								</ol>
							</div></div>
						</div>
					</div>
				</div>
			</div>
		</div>
        
		<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
		<script src="//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js"></script>
        <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.0.0/moment.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/handlebars.js/1.0.0-rc.3/handlebars.min.js"></script>
        <script src="assets/js/jquery.timer.js"></script>
        <script src="assets/js/ritechatter.js"></script>
        <script src="assets/js/chatter.js"></script>
        <script>
        
            var twitchat = "<?= $_GET['chat']; ?>";
            updatePage(twitchat, '#tweets_feed');
            
        </script>
	</body>
</html>