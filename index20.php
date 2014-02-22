<?php 
session_start();
//session_destroy();
include_once "script/common.php";
include_once 'class/game_manager.php';
$gamemanager = new game_manager($db);
//echo "Start<br>";
if(!isset($_SESSION['current_state'])){
    //echo "Getting state<br>";
    $_SESSION['current_state'] = $gamemanager->getState();
    $_SESSION['current_game'] = $gamemanager->getGame();
}
if($_SESSION['current_game'] != $gamemanager->getGame()){
    session_destroy();
    header("Location: index.php");
}
//echo $_SESSION['current_state'];
$timer = $gamemanager->getTimeLeftInState();
if($timer <= 0){
    $timer = 10;
}
if(isset($_POST['submit'])){
        //echo "Submit pressed<br>";
        if($_SESSION['current_state'] == 1){
            //echo "current state is one<br>";
            include_once 'script/check_state_one.php';
        }else if($_SESSION['current_state'] == 2){
            $timeToState3 = $gamemanager->getTimeToState3($_SESSION['current_game']);
            if($timeToState3 <= 0){
                if($gamemanager->getCurrentState() == 34){
                    $_SESSION['current_state'] = 3; 
                }
                if(isset($_SESSION['waiting'])){
                    unset($_SESSION['waiting']);
                }
            }else{
                $_SESSION['waiting'] = true;
            }
        }
}

if(($_SESSION['current_state'] == 1) || ($_SESSION['current_state'] == 2)){
    $timeToState3 = $gamemanager->getTimeToState3($_SESSION['current_game']);
    if($timeToState3 <= 0){                
       if(isset($_SESSION['waiting'])){
               if($gamemanager->getCurrentState() == 34){
                   $_SESSION['current_state'] = 3;
               }else{
                   $_SESSION['current_state'] = 100 +$gamemanager->getCurrentState();
               }
               unset($_SESSION['waiting']);
       }else{
           
       }
    }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Minority Games</title>
	<link rel="stylesheet" charset="utf-8" media="screen" href="css/style.css?<?php echo time();?>">        
	<link rel="stylesheet" charset="utf-8" media="screen" href="css/homepage.css?<?php echo time();?>">
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
        <!--[if IE]><script type="text/javascript" src="excanvas.js"></script><![endif]-->
        <script src="js/jquery.knob.js"></script>
        <script src="js/minority.js?<?php echo time();?>"></script>
        <script>
            $(function($) {

                $(".knob").knob({
                    change : function (value) {
                        //console.log("change : " + value);
                    },
                    release : function (value) {
                        //console.log(this.$.attr('value'));
                        console.log("release : " + value);
                    },
                    cancel : function () {
                        console.log("cancel : ", this);
                    },
                    draw : function () {

                        // "tron" case
                        if(this.$.data('skin') == 'tron') {

                            var a = this.angle(this.cv)  // Angle
                                , sa = this.startAngle          // Previous start angle
                                , sat = this.startAngle         // Start angle
                                , ea                            // Previous end angle
                                , eat = sat + a                 // End angle
                                , r = 1;

                            this.g.lineWidth = this.lineWidth;

                            this.o.cursor
                                && (sat = eat - 0.3)
                                && (eat = eat + 0.3);

                            if (this.o.displayPrevious) {
                                ea = this.startAngle + this.angle(this.v);
                                this.o.cursor
                                    && (sa = ea - 0.3)
                                    && (ea = ea + 0.3);
                                this.g.beginPath();
                                this.g.strokeStyle = this.pColor;
                                this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, sa, ea, false);
                                this.g.stroke();
                            }

                            this.g.beginPath();
                            this.g.strokeStyle = r ? this.o.fgColor : this.fgColor ;
                            this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, sat, eat, false);
                            this.g.stroke();

                            this.g.lineWidth = 2;
                            this.g.beginPath();
                            this.g.strokeStyle = this.o.fgColor;
                            this.g.arc( this.xy, this.xy, this.radius - this.lineWidth + 1 + this.lineWidth * 2 / 3, 0, 2 * Math.PI, false);
                            this.g.stroke();

                            return false;
                        }
                    }
                });

                // Example of infinite knob, iPod click wheel
                var v, up=0,down=0,i=0
                    ,$idir = $("div.idir")
                    ,$ival = $("div.ival")
                    ,incr = function() { i++; $idir.show().html("+").fadeOut(); $ival.html(i); }
                    ,decr = function() { i--; $idir.show().html("-").fadeOut(); $ival.html(i); };
                $("input.infinite").knob(
                                    {
                                    min : 0
                                    , max : 20
                                    , stopper : false
                                    , change : function () {
                                                    if(v > this.cv){
                                                        if(up){
                                                            decr();
                                                            up=0;
                                                        }else{up=1;down=0;}
                                                    } else {
                                                        if(v < this.cv){
                                                            if(down){
                                                                incr();
                                                                down=0;
                                                            }else{down=1;up=0;}
                                                        }
                                                    }
                                                    v = this.cv;
                                                }
                                    });
            });
        </script>
</head>
<body>

<div id="container">
    <table style="margin-top:50px;margin-left:50px;">
        <tr>
            <td colspan="2">
                <table>
                    <tr>
                        <td style="width:650px;">
                            <img src="images/minority.png">
                        </td>
                        <td>
                            <a href="https://twitter.com/share" class="twitter-share-button" data-url="http://www.minoritygames.com" data-text="I am playing the minority game, join me." data-hashtags="minoritygames">Tweet</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td>
                <div id="rules">
                    <center>Rules</center>
                </div> 
                <div id="actualrules">
                    <div style="padding:15px;padding-left:5px;">
                        <ol>
                            <li> The player who wins a minority game gets to post his appropriate message to the twitter status of the rest players.<br><br></li>
                            <li> By appropriate message we mean no cursing, offensive remarks, no racist or religious remarks, no use of I or try to impersonate someone else. All 
 messages will be validated by other users for appropriateness<br><br>
                            </li>
                            <li>
 At the start of the minority game you are given two items to choose from, pick one. If you pick the item that was least picked by the other users you advance to the next round. 
 This repeats until one or two players remain. If one player, remains that is the winner. If two players remain, the winner will be chosen randomly from the two. 
                            </li>
 
                        </ol>
                    </div>
                </div>
            </td>
            <td valign=top>
                <table valign=top>
                    <tr>
                        <td style="height:100px;width:300px">
                            <center>
                                <div class="demo">            
                                    <input class="knob" id="counter" data-width="100" data-height="100" data-max="600" data-thickness="0.2" readonly data-displayInput=true value="<?php echo $timer;?>">
                                    <script>
                                        function cd() {
                                            var $s = $("#counter");
                                            d = new Date();
				
                                            s = $s.val();
                                            s--;
                                            if(s == 0){
                                               location.reload();
                                            }
                                            
               
                                            $s.val(s).trigger("change");
                                            setTimeout("cd()", 1000);
                                        }
                                        cd();
                                    </script>
		
                                </div>
                            </center>
                        </td>
                    </tr>
                    <tr>
                        <td style="width:500px">
                            <?php
                                if($_SESSION['current_state'] == 1){
                                    include_once 'views/state1.php';
                                }else if($_SESSION['current_state'] == 2){
                                    include_once 'views/state2.php';                                    
                                }else if($_SESSION['current_state'] == 134){
                                    include_once 'views/state134.php';
                                }else if($_SESSION['current_state'] == 3){
                                    include_once 'views/state3.php';                                    
                                }else if($_SESSION['current_state'] == 4){
                                    include_once 'views/state4.php';
                                }
                            ?>
                        </td>
                    </tr>
            </table>
          </td>
       </tr>
    </table>
</div>   
</body>
</html>