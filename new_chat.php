<?php

require_once("system/init.php");
$chat = $_GET['chat'];

$chatInfo = Chat::get_chat_info($chat);

if($twitUser->isAuthed()) { $twitterUser = $tweeter->userData(); }
if(!$twitUser->isAuthed()){ $_SESSION['chat_no_auth_page'] = "http://ritechatter.com/new_chat.php?chat=".$chat; }

?>
<!DOCTYPE html>
<html>

	<head>
		<title>Ritechatter &mdash; #<?= $chat ?></title>
		<link type="text/css" rel="stylesheet" href="assets/css/chat.css">
		<link type="text/css" rel="stylesheet" href="assets/css/groundwork.css">
    	<!--[if IE]><link type="text/css" rel="stylesheet" href="assets/css/groundwork-ie.css"><![endif]-->
    	<!--[if lt IE 9]><script type="text/javascript" src="assets/js/libs/html5shiv.min.js"></script><![endif]-->
    	<!--[if IE 7]><link type="text/css" rel="stylesheet" href="assets/css/font-awesome-ie7.min.css"><![endif]-->
	</head>
	
	<body>
	
		<div class="container">
			<article class="row equalize full-ui">
				<section class="one fourth left border-left ui-section">
					<!-- Start TweetBox -->
					<? if($twitUser->isAuthed()) : //show all the options ?>
					<div class="options">
						<div class="one fifth ui-option"><a href="#share-modal" class="tooltip asphalt button block" role="tooltip" data-title="Share this chat with others!"><i class="icon-share-alt icon-large"></i></a></div>
						<div class="one fifth ui-option"><a href="#filter-modal" class="tooltip asphalt button block" role="tooltip" data-title="Filter through tweets and decide what shows and what doesn't"><i class="icon-filter icon-large"></i></a></div>
						<div class="one fifth ui-option"><a class="tooltip asphalt button block" id="show-hidden" role="tooltip" data-title="Show all things that you have hidden (RTs,Links,and Blocked People)"><i class="icon-eye-open icon-large"></i></a></div>
						<div class="one fifth ui-option"><a href="#settings-modal" class="tooltip asphalt button block" role="tooltip" data-title="View and Edit Settings"><i class="icon-cogs icon-large"></i></a></div>
						<div class="one fifth ui-option"><a href="/logout/" role="tooltip" data-title="Logout of Twitter" class="tooltip asphalt button block"><i class="icon-signout icon-large"></i></a></div>
					</div>
					<div class="tweetbox info"><p class="count" id="charCount"></p><textarea class="tooltip" role="tooltip" data-title="We already add the hashtag for you. To add more hashtags, go to settings at the top." id="tweetbox" maxlength="120" placeholder="Type Tweet Here...."></textarea><button type="submit" id="tweetBtnSubmit" class="block blue">Send Tweet</button></div>
					<hr>
					<dl class="padded">
						<dt>Feed Controls</dt>
						<dd></dd>
						<dd><span class="button block tooltip" title="You can also hover tweets to pause the feed." id="pause_play_btn" role="tooltip">Play/Pause Feed</span></dd>
						<dd>Refresh Speed: <span id="range">2</span> seconds<input id="speedControl" type="range" min="2" max="10" value="2" step="2" onchange="document.getElementById('range').innerHTML=this.value"</dd>
						<dd></dd>
					</dl>
					<div class="feed_controls padded">
						<div class=""></div>
					</div>
					<? else: //show the user where to login ?>
					<div class="callout info square">
						<h3>Want to join in the conversation?</h3>
						<a class="info button" href="/authin/">Login with Twitter</a>
						<hr>
						<dl class="padded">
							<dt>What do I get when I Login in with Twitter?</dt>
							<dd>
								<ul><li>Tweeting</li><li>Realtime Feed</li><li>Questions Update</li><li>Display Options</li><li>Filters</li><li>Follow, Retweet, Favorite, etc...</li></ul>
							</dd>
						</dl>
					</div>
					<? endif; //fuck it ?>
					<!-- End TweetBox -->
				</section>
				<section class="one fourth right-two border-left border-right ui-section">
					
					<h1 class="padded responsive">#<?= $chat ?></h1>
					<hr>
					<!--
					<dl class="padded">
						<dt>Chat Questions</dt>
						<dd>
						<ol class="">
							<li>Is this question number 1?</li>
							<li class="warning">This must be question number 2?</li>
							<li>Hey there?</li>
							<li>Is this a question?</li>
						</ol>
						</dd>
					</dl>
					<dl class="padded">
						<dt>Current Question</dt><dd class="warning"><strong>This must be question number 2?</strong></dd>
					</dl>
					-->
					<hr>
					<script id="description_template" type="text/x-handlebars-template"></script>
					<dl class="padded">
						<dt>Description</dt><dd><?= $chatinfo['description']; ?></dd>
					</dl>
					
				</section>
				<section class="two fourths left-one border-left ui-section">
					<div class="tweets padded" id="tweetsArea">
						
						<script id="tweet_template" type="text/x-handlebars-template">
						<div class="tweet {{ handle }}" id="{{ tweet_id }}">
							<div class="row equalize tweet_content {{ highlight_color }}">
								<div class="one eighth left">
									<img src="{{ profile_img }}" width="48px" height="48px">
								</div>
								<div class="seven eighths left">
									<p><span class="blue">{{ full_name }} (<a href="http://twitter.com/{{ handle }}/" target="_new">@{{ handle }}</a>)</span> &mdash; {{ tweet_text }} </p>
								</div>
							</div>
							<div class="row equalize info hide tweet_actions {{ highlight_color }}">
								<? if($twitUser->isAuthed()) : ?>
									<div class="action three thirds left" data-user="{{ user_id }}" data-tweet="{{ tweet_id }}">
										<span class="tweet_ops fav">Favorite</span>
										<span class="tweet_ops reply">Reply</span>
										<span class="tweet_ops rt">Retweet</span>
									</div>
								<? endif; ?>
								<div class="three thirds left">
									<small>{{ time }}</small>
								</div>
							</div>
						</div>
						</script>
						
					</div>
				</section>
			</article>
		</div>
		
		<div id="filter-modal" class="modal">
			<div class="row equalize">
				<div class="one half padded">
        			<input id="option1" type="checkbox" name="hideRt" checked="checked">
        			<label for="option1" class="inline">Hide Retweets</label>
        		</div>
        		<div class="one half padded">
        			<input id="option2" type="checkbox" name="pauseHover" checked="checked">
        			<label for="option2" class="inline">Pause Stream (when hovering over a tweet)</label>
        		</div>
        	</div>
		</div>
		<div id="settings-modal" class="modal">
			<div class="row equalize">
				<div class="one half padded">
					TEST
				</div>
				<div class="one half padded">
					CONTENT
				</div>
			</div>
		</div>
		<div id="share-modal" class="modal">
			<div class="row equalize">
				<div class="one half padded">
					TEST
				</div>
				<div class="one half padded">
					CONTENT
				</div>
			</div>
		</div>
		
		<script src="assets/js/jquery.js"></script>
		<script src="http://cdnjs.cloudflare.com/ajax/libs/handlebars.js/1.0.0-rc.3/handlebars.min.js"></script>
		<script src="assets/js/groundwork.all.js"></script>
		<script src="assets/js/jquery.timer.js"></script>
		<script type="text/javascript">
			IS_AUTHED = <?= ($twitUser->isAuthed() ? 'true' : 'false') ?>;
			CURRENT_USER = '<?= ($twitUser->isAuthed() ? $twitterUser['screen_name'] : '') ?>';
			CHAT_OWNER = '<?= $chatInfo['owner']; ?>';
			CHAT_MODS = '<?= $chatInfo['mods']; ?>';
			CHAT = '<?= $chat ?>';
			CHAT_HASHTAG = '<?= "#".$chat ?>';
			CHAT_LASTID = 0;
			
			OPTION_hideRT = $('#option1');
			OPTION_pauseHover = $('#option2');
			// other option selectors here.
			
			tweetTemplateScript = $('#tweet_template').html();
			tweet_template = Handlebars.compile(tweetTemplateScript, {noEscape: true});
			
			tweets_id = [];
            //More Global Variables can be added if needed.....
		</script>
		<script src="assets/js/chat_all.js"></script>
		<? if(!$twitUser->isAuthed()) : ?>
			<script src="assets/js/chatter.js"></script>
		<? else : ?>
			<script src="assets/js/chatter_realtime.js"></script>
		<? endif; ?>
		
	</body>

</html>