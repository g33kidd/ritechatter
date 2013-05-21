$(document).ready(function() {
    
    var tweetTemplateScript = $('#tweet_template').html();
    var tweet_template = Handlebars.compile(tweetTemplateScript, {noEscape: true});
    
	$(document).on('keydown',function(ev){
	    var nodeName = ev.target.nodeName;
	    if ( 'INPUT' == nodeName || 'TEXTAREA' == nodeName ) { return; }
	    $('.searchbox').val('').trigger('focus');
	});
	
	$('#tweetBtnSubmit').on('keyup focus', function() {
	});
	
	$('#tweetBtnSubmit').click(function() {
		var tweet = $('#tweetbox').val();
		if(tweet.length <= 120 && tweet.length > 0){
			$.ajax({
				url: "api/post/tweet/",
				type: "POST",
				dataType: "json",
				data: {status: tweet},
				success: function(twit) {
					console.log(twit);
					var tweet_text = linkify_entities(twit);
					var tweetData = {time:twitDate(twit.created_at), highlight_color:"info",handle:twit.user.screen_name,profile_img:twit.user.profile_image_url,full_name:twit.user.name,user_id:twit.user.id_str,tweet_id:twit.id_str,tweet_text:tweet_text};
					$('#tweetsArea').prepend(tweet_template(tweetData));
					$('#'+twit.id_str).hide().slideDown(800);
					lastid = twit.id_str;
				}
			});
		}
	});
	
	$.ajax({
		url: "api/get/tweets/"+ CHAT +"/15",
		type: "GET",
		dataType: "json",
		success: function(t) {
			$(t.statuses).each(function() {
				if(CURRENT_USER == this.user.screen_name){ var highlight_color = "alert"; }else{ var highlight_color = "info"; }
				var tweet_text = linkify_entities(this);
				var tweetData = {time:twitDate(this.created_at), highlight_color:highlight_color,handle:this.user.screen_name,profile_img:this.user.profile_image_url,full_name:this.user.name,user_id:this.user.id_str,tweet_id:this.id_str,tweet_text:tweet_text};
				$('#tweetsArea').append(tweet_template(tweetData));
				$('#'+this.id_str).hide().slideDown(800);
			});
		}
	});

});

// pass in the 'created_at' string returned from twitter //
// stamp arrives formatted as Tue Apr 07 22:52:51 +0000 2009 //
function twitDate(text)
{		
	var newtext = text.replace(/(\+\S+) (.*)/, '$2 $1')	
	var date = new Date(Date.parse(text)).toLocaleDateString();
	var time = new Date(Date.parse(text)).toLocaleTimeString();
	return date +' \u00B7 ' + time;
}

function escapeHTML(text) {
    return $('<div/>').text(text).html()
}

function linkify_entities(tweet) {
    if (!(tweet.entities)) {
        return escapeHTML(tweet.text)
    }
    
    // This is very naive, should find a better way to parse this
    var index_map = {}
    
    $.each(tweet.entities.urls, function(i,entry) {
        index_map[entry.indices[0]] = [entry.indices[1], function(text) {return "<a href='"+escapeHTML(entry.url)+"' target='_new'>"+escapeHTML(text)+"</a>"}]
    })
    
    $.each(tweet.entities.hashtags, function(i,entry) {
        index_map[entry.indices[0]] = [entry.indices[1], function(text) {return "<a href='http://twitter.com/search?q="+escape("#"+entry.text)+"' target='_new'>"+escapeHTML(text)+"</a>"}]
    })
    
    $.each(tweet.entities.user_mentions, function(i,entry) {
        index_map[entry.indices[0]] = [entry.indices[1], function(text) {return "<a title='"+escapeHTML(entry.name)+"' href='http://twitter.com/"+escapeHTML(entry.screen_name)+"' target='_new'>"+escapeHTML(text)+"</a>"}]
    })
    
    var result = ""
    var last_i = 0
    var i = 0
    
    // iterate through the string looking for matches in the index_map
    for (i=0; i < tweet.text.length; ++i) {
        var ind = index_map[i]
        if (ind) {
            var end = ind[0]
            var func = ind[1]
            if (i > last_i) {
                result += escapeHTML(tweet.text.substring(last_i, i))
            }
            result += func(tweet.text.substring(i, end))
            i = end - 1
            last_i = end
        }
    }
    
    if (i > last_i) {
        result += escapeHTML(tweet.text.substring(last_i, i))
    }
    
    return result
}

(function($) {
	$.fn.extend({
		limiter: function(limit, elem, hashtag) {
			$(this).on("keyup focus", function() {
				setCount(this, elem);
			});
			function setCount(src, elem) {
				var chars = src.value.length;
				if(chars > limit) {
					src.value = src.value.substr(0, limit);
					chars = limit;
				}
				elem.html("<small>" + (limit - chars) + " left</small>");
			}
			setCount($(this)[0],elem);
		}
	});
})(jQuery);