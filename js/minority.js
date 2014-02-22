
function validateTweet(){
    var handle = $.trim($("#handle").val());
    var tweet = $.trim($("#tweet").val());
    var error = false;
    if(handle.length == 0){
        error = true;  
        $("#error_bar").html("<center>Please enter a valid twitter handle.</center>");
    }
    
    if(tweet.length >140){
        error = true;
        $("#error_bar").html("<center>Please enter a tweet of 140 characters or less.</center>");
    }
    if(handle.length > 25){
        error = true;
        $("#error_bar").html("<center>Please enter a twitter handle of 25 characters or less.</center>");   
        
    }
    if(tweet.length ==0){
        error = true; 
        $("#error_bar").html("<center>Please enter tweet.</center>");
    
    }
    if(!error){
        var regex = /[^a-zA-Z0-9]/;
        var match = regex.test(handle);
        if(match){
            $("#error_bar").html("<center>Twitter handles must be alphanumeric</center>");
            error = true;
        }
    }
    if(error){
        $("#error_bar").css("visibility","visible");
        return false;
    }
    return true;
}

function updatecount(){
    var tweet = $.trim($("#tweet").val());
    var count = 140 - tweet.length;
    $("#char_count").html(""+count);
    if(count < 0){
        $("#char_count").css("color","#ff0000");
    }else{
        $("#char_count").css("color","white");
    }
}