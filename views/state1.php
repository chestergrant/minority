<span style="color:white;margin-top:0px;margin-left:0px;">
    The timer indicates time left before the next minority game begins. Before the timer runs out you must accomplish the following:
    <ol>
        <li>Enter your tweet that you want tweeted to the world.</li>
        <li>Tweet that you are playing the minority game so that we can know it's your account</li>
        <li>Validate other users tweets for appropriateness(see rules)</li>
        <li>Validate other users account, ie that they have tweeted they are playing the minority game and have atleast 15 followers</li>
    </ol>
    <div style="font-weight:bold;font-size:26px;color:black;">Step 1</div>
    <div id="error_bar" class="error-bubble"></div>
    <form method="post" action="#" onsubmit="return validateTweet()">
        <input class="flat" name="handle" id="handle" type="text" placeholder="Twitter Handle eg. @xyzgra">
        <textarea class="flat" onkeyup="updatecount()" id="tweet" name="tweet" placeholder="Tweet"></textarea>
        <input type="hidden" name="state" value="1">
        <span id="char_count" style="float:right; margin-right:30px;">140</span>
        <input type="submit" class="flatsubmit" name ="submit" value="Submit">
    </form>
</span>