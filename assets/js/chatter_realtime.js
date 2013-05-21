$(document).ready(function() {
	
	var tweetTemplateScript = $('#tweet_template').html();
    var tweet_template = Handlebars.compile(tweetTemplateScript, {noEscape: true});
    
    var hashtag = 120 - CHAT_HASHTAG.length + 1;
    $('#tweetbox').limiter(hashtag, $('#charCount'));
	
	$.ajax({
		url: "api/get/tweets/"+ CHAT +"/25",
		type: "GET",
		dataType: "json",
		success: function(t) {
			console.log(t);
			var statuses = t.statuses;
			statuses.reverse();
			if(CHAT_LASTID !== t.search_metadata.max_id_str){
				$(statuses).each(function() {
					if(CHAT_LASTID !== this.id_str || this.id_str < CHAT_LASTID) {
					
						// should there be a default color or just white???
						//var highlight_color = "_7";
						
						if(CURRENT_USER == this.user.screen_name){ 
							var highlight_color = "_10";
						}else if(CHAT_OWNER == this.user.screen_name){
							var highlight_color = "_4";
						}
						
						if(this.retweeted == true){
							var highlight_color = "_9";
						}
						
						var tweet_text = linkify_entities(this);
						var realTweetData = {time:twitDate(this.created_at), highlight_color:highlight_color,handle:this.user.screen_name,profile_img:this.user.profile_image_url,full_name:this.user.name,user_id:this.user.id_str,tweet_id:"tweet_"+this.id_str,tweet_text:tweet_text};
						
						$('#tweetsArea').prepend(tweet_template(realTweetData));
						$('#tweet_'+this.id_str).hide().slideDown('fast');
						
						CHAT_LASTID = this.id_str;
						tweets_id.push(this.id);
					}
				});
			}
		}
	});
	
	if(IS_AUTHED){
	
		var realtime = $.timer(function() {
			$.ajax({
				url: "api/get/tweets/"+ CHAT +"/1",
				type: "GET",
				dataType: "json",
				success: function(t) {
					console.log(t);
					if(CHAT_LASTID !== t.search_metadata.max_id_str){
						$(t.statuses).each(function() {
						
							var found = $.inArray(this.id, tweets_id);
							console.log(found);
							if(CHAT_LASTID !== this.id_str && found < 0){
								
								if(CURRENT_USER == this.user.screen_name){
									var highlight_color = "_10";
								}else if(CHAT_OWNER == this.user.screen_name){
									var highlight_color = "_4";
								}
								
								if(this.retweeted == true){
									var highlight_color = "_9";
								}
								
								var tweet_text = linkify_entities(this);
								var realTweetData = {time:twitDate(this.created_at), highlight_color:highlight_color,handle:this.user.screen_name,profile_img:this.user.profile_image_url,full_name:this.user.name,user_id:this.user.id_str,tweet_id:"tweet_"+this.id_str,tweet_text:tweet_text};
								$('#tweetsArea').prepend(tweet_template(realTweetData));
								$('#tweet_'+this.id_str).hide().slideDown('fast');
								
								CHAT_LASTID = this.id_str;
								tweets_id.push(this.id);
								
							}
						});
					}
				}
			});

		});
		
		$('#speedControl').change(function() {
			realtime.set({time:this.value * 1000})
		});
		realtime.set({time:3000,autostart:true});
		if((OPTION_pauseHover).is(':checked')){
			$(document).on("mouseover", ".tweet", function() { realtime.pause(); $(this).find('.tweet_actions').slideDown(200); });
    		$(document).on("mouseleave", ".tweet", function() { realtime.play(); $(this).find('.tweet_actions').slideUp(100); });
		}else{
			$(document).on("mouseover", ".tweet", function() { $(this).find('.tweet_actions').slideDown(200); });
    		$(document).on("mouseleave", ".tweet", function() { $(this).find('.tweet_actions').slideUp(100); });
		}
	}
	
	$('#tweetBtnSubmit').click(function() {
		var tweet = $('#tweetbox').val() + " " + CHAT_HASHTAG;
		if(tweet.length <= 120 && tweet.length > 0){
			$.ajax({
				url: "api/post/tweet/",
				type: "POST",
				dataType: "json",
				data: {status: tweet},
				success: function(twit) {
					console.log(twit);
					
					var tweet_text = linkify_entities(twit);
					var tweetData = {time:twitDate(twit.created_at), highlight_color:"_10",handle:twit.user.screen_name,profile_img:twit.user.profile_image_url,full_name:twit.user.name,user_id:twit.user.id_str,tweet_id:"tweet_"+twit.id_str,tweet_text:tweet_text};
					
					$('#tweetsArea').prepend(tweet_template(tweetData));
					//$('#tweet_'+twit.id_str).hide().slideDown(800);
					
					CHAT_LASTID = twit.id_str;
					tweets_id.push(twit.id);
				}
			});
		}
	});
	
});