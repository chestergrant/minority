<span style="color:white;margin-top:0px;margin-left:0px;">
    The timer indicates time left before the next minority game begins. Before the timer runs out you must accomplish the following:
		<ol>
                    <li><strike>Enter your tweet that you want tweeted to the world.</strike></li>
                    <li><strike>Tweet that you are playing the minority game so that we can know it's your account</strike></li>
                    <li><strike>Validate other users tweets for appropriateness(see rules)</strike></li>
                    <li>Validate other users account, ie that they have tweeted they are playing the minority game and have atleast 15 followers</li>
                </ol>
		<div style="font-weight:bold;font-size:26px;color:black;">Step 4<span style="color:white;">(<?php echo $stage; ?>)</span></div>
		Click on the link to the twitter account below. Click valid if the user has as a tweet within the last 10mins that they are playing the minority game and they have atleast 15 followers, otherwise click invalid.
		<div style="background:rgba(255,255,255,0.5);color:black; width:500px">
                    Twitter Account: <a href="<?php echo $handle_link;?>" target="_blank"><?php $handle;?></a>
                </div>
		<form>
                    <table style="width:500px;">
                        <tr>
                            <td>
                                <input type="submit" name ="submit1" class="flatsubmit" style="width:170px !important;" value="Valid">
                            </td>
                            <td>
                                <input type="submit" name="submit2" class="flatsubmit" style="width:170px !important;" value="Invalid">
                            </td>
                        </tr>
                    </table>
                </form>
		
</span>
