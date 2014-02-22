<span style="color:white;margin-top:0px;margin-left:0px;">
    The timer indicates time left before the next minority game begins. Before the timer runs out you must accomplish the following:
    <ol>
        <li><strike>Enter your tweet that you want tweeted to the world.</strike></li>
	<li>Tweet that you are playing the minority game so that we can know it's your account</li>
	<li>Validate other users tweets for appropriateness(see rules)</li>
	<li>Validate other users account, ie that they have tweeted they are playing the minority game and have atleast 15 followers</li></ol>
	<div style="font-weight:bold;font-size:26px;color:black;">Step 2</div>
        <?php if(!isset($_SESSION['waiting'])){?>
                    Click on Tweet button on the top of this page then click Done button when you are finished tweeting that you are playing the minority game.
                    <form>
                        <input type="submit" name="submit" class="flatsubmit" value="Done">
                    </form>
         <?php }else{?>
                    Please wait while the others finish steps 1 and 2.
         <?php }?>
</span>
