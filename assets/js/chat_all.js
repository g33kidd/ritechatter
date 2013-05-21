// pass in the 'created_at' string returned from twitter //
// stamp arrives formatted as Tue Apr 07 22:52:51 +0000 2009 //
function twitDate(text)
{		
	var newtext = text.replace(/(\+\S+) (.*)/, '$2 $1')	
	var date = new Date(Date.parse(text)).toLocaleDateString();
	var time = new Date(Date.parse(text)).toLocaleTimeString();
	return date +' \u00B7 ' + time;
}

function realtime(term, me) {
	
	var tweetTemplateScript = $('#tweet_template').html();
    var tweet_template = Handlebars.compile(tweetTemplateScript, {noEscape: true});
    var lastid = "";
	var realtime = $.timer(function() {
		
		$.ajax({
			url: "api/get/tweets/"+ term + "/1",
			type: "GET",
			dataType: "json",
			success: function(t) {
				
				if(lastid !== t.search_metadata.max_id_str){
					$(t.statuses).each(function() {
						if(me == this.user.screen_name){ var highlight_color = "alert"; }else{ var highlight_color = "info"; }
						var tweet_text = linkify_entities(this);
						var tweetData = {time:twitDate(this.created_at), highlight_color:highlight_color,handle:this.user.screen_name,profile_img:this.user.profile_image_url,full_name:this.user.name,user_id:this.user.id_str,tweet_id:this.id_str,tweet_text:tweet_text};
						$('#tweetsArea').prepend(tweet_template(tweetData));
						$('#'+this.id_str).hide().slideDown('slow');
					});
				}
				
				lastid = t.search_metadata.max_id_str;
			}
		});
	});
	
	realtime.set({time:3000,autostart:true});
	$(document).on("mouseover", ".tweet", function() { realtime.pause(); });
    $(document).on("mouseleave", ".tweet", function() { realtime.play(); });
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

function userInfo() {
	$.ajax({
		url: "api/get/user_credentials",
		type: "GET",
		dataType: "json",
		success: function(u) {
			console.log(u);
		}
	});
}

function user_authed() {
	$.ajax({
		url: "api/get/isAuthed",
		type: "GET",
		dataType: "json",
		success: function(u){
			if(u.msg === true){
				alert("User is Authed");
			}else{
				alert("User is not Authed");
			}
		}
	});
}

(function($) {
	$.fn.extend({
		limiter: function(limit, elem) {
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

