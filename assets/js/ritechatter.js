
String.prototype.links = function() {
    return this.replace(/[A\-Za\-z]+:\/\/[A\-Za\-z0\-9\-_]+\.[A\-Za\-z0\-9\-_:%&\?\/.=]+/g, function(link) {
        return '<a href="'+ link +'" target="_new">'+ link +'</a>';
    });
}
String.prototype.user = function() {
    return this.replace(/[@]+[A\-Za\-z0\-9\-_]+/g, function(user) {
        var username = user.replace("@", "");
        return '<a class="twitter-user mention" href="http://twitter.com/'+ username +'" target="_new">'+ user +'</a>';
    });
}
String.prototype.hashtags = function() {
    return this.replace(/[#]+[A\-Za\-z0\-9\-_]+/g, function(hashtag) {
        var hash = hashtag.replace("#", "");
        return hashtag.link("http://ritechatter.com/chat/" + hash);
    });
}
String.prototype.RTs = function() {
    // do this shit later...
}

function updatePage(twitchat, tweet) {
    var lastid = "";
    var ammount = 15;
    var url = "http://search.twitter.com/search.json?q=%23"+ twitchat +"&rpp="+ ammount +"&callback=?";
    
    var bulk_tweetTemplateScript = $('#bulk_tweet_template').html();
    var bulk_tweet_template = Handlebars.compile (bulk_tweetTemplateScript);
    var tweetTemplateScript = $('#tweet_template').html();
    var tweet_template = Handlebars.compile (tweetTemplateScript);
    
    console.log("The current chat is ours? " + is_our_chat(twitchat));
    
    if(is_our_chat()){ $('#description').show(); }else{ $('#description').hide(); }
    
    $.ajax({
        url: "oauth.php?url="+encodeURIComponent('search/tweets.json?q=%23'+ twitchat +'&count=15'),
        type: "GET",
        dataType: "json",
        success: function(data) {
            $(data.results).each(function() {
                
            });
            $(tweet).prepend();
        }
    });
    
    var bulkTweetData = {tweets:[
        {from_user:"g33k_kidd",profile_image_url:"https://lh6.googleusercontent.com/-rB1gpWbtrhM/AAAAAAAAAAI/AAAAAAAAAAA/6IpjQNuqrE8/s96-c/photo.jpg",tweet_id:"12345678",tweet_text:"Phasellus tortor dolor, varius ut tincidunt in, scelerisque sed dolor. Proin id orci velit, vitae eleifend amet. #"+ twitchat +""},
        {from_user:"g33k_kidd",profile_image_url:"https://lh6.googleusercontent.com/-rB1gpWbtrhM/AAAAAAAAAAI/AAAAAAAAAAA/6IpjQNuqrE8/s96-c/photo.jpg",tweet_id:"32543234",tweet_text:"Phasellus tortor dolor, varius ut tincidunt in, scelerisque sed dolor. Proin id orci velit, vitae eleifend amet. #"+ twitchat +""},
        {from_user:"g33k_kidd",profile_image_url:"https://lh6.googleusercontent.com/-rB1gpWbtrhM/AAAAAAAAAAI/AAAAAAAAAAA/6IpjQNuqrE8/s96-c/photo.jpg",tweet_id:"45321346",tweet_text:"Phasellus tortor dolor, varius ut tincidunt in, scelerisque sed dolor. Proin id orci velit, vitae eleifend amet. #"+ twitchat +""},
        {from_user:"g33k_kidd",profile_image_url:"https://lh6.googleusercontent.com/-rB1gpWbtrhM/AAAAAAAAAAI/AAAAAAAAAAA/6IpjQNuqrE8/s96-c/photo.jpg",tweet_id:"87745634",tweet_text:"Phasellus tortor dolor, varius ut tincidunt in, scelerisque sed dolor. Proin id orci velit, vitae eleifend amet. #"+ twitchat +""},
        {from_user:"g33k_kidd",profile_image_url:"https://lh6.googleusercontent.com/-rB1gpWbtrhM/AAAAAAAAAAI/AAAAAAAAAAA/6IpjQNuqrE8/s96-c/photo.jpg",tweet_id:"54545323",tweet_text:"Phasellus tortor dolor, varius ut tincidunt in, scelerisque sed dolor. Proin id orci velit, vitae eleifend amet. #"+ twitchat +""}
    ]};
    
    $(tweet).prepend(bulk_tweet_template(bulkTweetData));
    
    //var tweetData = {from_user:"g33k_kidd",profile_image_url:"https://lh6.googleusercontent.com/-rB1gpWbtrhM/AAAAAAAAAAI/AAAAAAAAAAA/6IpjQNuqrE8/s96-c/photo.jpg",tweet_id:"12345678",tweet_text:"Phasellus tortor dolor, varius ut tincidunt in, scelerisque sed dolor. Proin id orci velit, vitae eleifend amet. #"+ twitchat +""};
    var url = "oauth.php?url="+encodeURIComponent('search/tweets.json?q=%23'+ twitchat +'&count=1')+"";
    console.log(url);
    /*var realtimer = $.timer(function() {
        $.ajax({
            url: "oauth.php?url="+encodeURIComponent('search/tweets.json?q=%23'+ twitchat +'&count=1')+"",
            type: "GET",
            dataType: "json",
            success: function(data) {
                console.log(data);
                /*
                $(data.results).each(function() {
                    if(lastid == this.max_id){
                        return;
                    }else{
                        var tweetData = {from_user:this.from_user,profile_image_url:this.profile_image_url,tweet_id:this.id,tweet_text:this.text};
                        console.log(tweetData);
                        lastid = this.id;
                        $(tweet).prepend(tweet_template(tweetData));
                    }
                });
            }
        });
    });*/
    
    realtimer.set({time:3000,autostart:true});
    $(document).on("mouseover", ".tweet", function() { realtimer.pause(); });
    $(document).on("mouseleave", ".tweet", function() { realtimer.play(); });
    
    /*
    setInterval(function() {
        
    }, 3000);
    */
}

// get the chat id of an existing hashtag in our db.
function getChatId(chat) {
    var requestUrl = "api/get/chatid/"+chat;
    $.ajax({
        url: requestUrl,
        type: "GET",
        dataType: "json",
        success: function(data) {
            return data.msg;
        }
    });
}

//check if an event is currently taking place
function event_now(chat) {
    var requestUrl = "api/get/event_now/"+chat;
    $.ajax({
        url: requestUrl,
        type: "GET",
        dataType: "json",
        success: function(data) {
            console.log(data);
            if(data.msg === true){
                return true;
            }else{
                return false;
            }
        },
        error: function() {
            alert("\nTHERE WAS AN ERROR! is chat event now function\n");
        }
    });
}

//check if the hashtag is affiliated with a chat in our DB
function is_our_chat(chat) {
    var requestUrl = "api/get/is_chat/"+chat;
    console.log(requestUrl);
    $.ajax({
        url: requestUrl,
        type: "GET",
        dataType: "json",
        success: function(data) {
            console.log(data.msg);
            if(data.msg === true){
                return true;
            }else{
                return false;
            }
        },
        error: function() {
            alert("\nTHERE WAS AN ERROR! is our chat function\n");
        }
    });
}

function chat_info() {
    // gets information and displays like moderators,etc...
}