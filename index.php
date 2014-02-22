<?php 
session_start();
$ip = $_SERVER['REMOTE_ADDR'];
$link_id = "";

include_once "script/common.php";
include_once 'class/minoritygames.php';
$gamemanager = new minoritygames($db);
if((!isset($_SESSION['userid']))&&(isset($_REQUEST['view']))){
    unset($_REQUEST['view']);
}
if(isset($_REQUEST['link_id'])){
    $link_id = $_REQUEST['link_id'];   
    if($gamemanager->validLink($link_id)){
        $_SESSION['slink_id'] = $link_id;
        $_SESSION['fail'] = $gamemanager->failed($link_id);
    }else{
        $link_id = "";
    }
    
}
if(!isset($_SESSION['fail'])){
  $_SESSION['fail'] = false;    
}
if(!isset($_SESSION['userid'])){
   
    $hasID = $gamemanager->hasID($ip);
    $id = "";
    if(!$hasID){
        if($link_id != ""){            
            $id = $link_id;
        }else{
            $id = $gamemanager->createId($ip);
        }

    }else{
        $id = $gamemanager->getId($ip);
    }
    $_SESSION['userid'] = $id;
    
}
if(isset($_POST['submit'])){
    $page = 2; 
    $url1 = $gamemanager->getUrl($_SESSION['userid'],$ip,1);
    $url2 = $gamemanager->getUrl($_SESSION['userid'],$ip,2);
}else{
    $page = 1;
}
if(isset($_REQUEST['view'])){
    $page = 3;
    $totalreach = $gamemanager->reach($_SESSION['userid']);
    $url1 = $gamemanager->getUrl($_SESSION['userid'],$ip,1);
    $url2 = $gamemanager->getUrl($_SESSION['userid'],$ip,2);
    $id1 = $gamemanager->childid($_SESSION['userid'],1);
    $id2 = $gamemanager->childid($_SESSION['userid'],2);
    $totalurl1 = $gamemanager->reach($id1);
    $totalurl2 = $gamemanager->reach($id2);
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
       
        <script src="js/minority.js?<?php echo time();?>"></script>
        
</head>
<body>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-48129606-1', 'minoritygames.com');
  ga('send', 'pageview');

</script>
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
            <td valign="top">
                <div id="rules">
                    <center>Rules</center>
                </div> 
                <div id="actualrules">
                    <div style="padding:15px;padding-left:5px;">
                        <ol>
                            <li>Minority Games is about practising creating a network effect.</li>
                            <li>Select two friends who are willing to practice with you.</li>
                            <li>Send them a copy of the link generated when you press play and tell them they have 24 hours to follow it.</li>
                            <li>Your score depends on the number of persons you were able to reach originating from the links you sent without the links expiring</li>
                            <li>Please don't place the link in a public area like twitter or facebook.</li>
                            <li>Play as much time as you like</li>
                        </ol>
                    </div>
                </div>
            </td>
            <td valign=top width="400px";>
               <?php if ($page == 1){?>
                    <span style="color:white;margin-top:0px;margin-left:0px;width:400px;text-align:justify;text-justify:inter-word;">
                            <?php if((isset($_SESSION['fail']))&&($_SESSION['fail']==true)){?>
                                        You have failed to click on the link in the required time. The next time try to be a little quicker in responding to a link.
                            <?php }else { ?>
                                The minority game is a simply game it test to see how much people you can reach with a link.  Many of us have an idea, book, a cause or business
                                we would like to share with the world, but some how the word is not getting out. Playing this game you can see if you even have enough clout
                                to spread a message.  Also, this is kind of an experiment to see if with practice a message can be better spread. Think of this as playing a game of catch with some friends.
                                <?php if(isset($_SESSION['slink_id'])){?>
                                        <br>You have been selected as a gatekeeper. You decide if the rest of your network sees this link. You yourself might not enjoy this game, but someone in your network might so please pass it along by clicking play.
                                <?php } ?>
                            <?php } ?>
                            
                    </span>
                    <?php if(!$_SESSION['fail']){ ?>
                        <center>
                            <form method="post">
                                <input type="submit" style="width:250px !important;" class="flatsubmit" name ="submit" value="Play">
                            </form>
                        </center>
                    <?php } ?>
               <?php }elseif($page == 2){?>
                        <span style="color:white;margin-top:0px;margin-left:0px;width:400px;text-align:justify;text-justify:inter-word;">
                          Thanks for playing. The game is simple give these two links to two different person and tell them they have 24 hours before the link expires to click on them.
                          Your score will be determined by the number of persons your link was able to reach(Please don't click on your own link).
                          <div style="background:rgba(255,255,255,0.5);color:black; width:400px;text-align:left;">1. <?php echo $url1;?></div><br>
                          <div style="background:rgba(255,255,255,0.5);color:black; width:400px;text-align:left;">2. <?php echo $url2;?></div><br>
                          <center><a style="text-decoration: none;" href="index.php?view=<?php echo $_SESSION['userid'];?>"><div class="flatsubmit" style="width: 250px !important"><center>View Result</center></div></center>
                        </span>
                <?php }else{ ?>
                        <span style="color:white;margin-top:0px;margin-left:0px;width:400px;text-align:justify;text-justify:inter-word;">
                          <center><span style="font-size:26px;">Reach:<?php echo $totalreach;?></span><br></center>
                          <center>
                              <table>
                                  <tr>
                                      <td>Link</td>
                                      <td>Reach</td>
                                  </tr>
                                  <tr>
                                      <td><?php echo $url1;?></td>
                                      <td><?php echo $totalurl1;?></td>
                                  </tr>
                                  <tr>
                                      <td><?php echo $url2;?></td>
                                      <td><?php echo $totalurl2;?></td>
                                  </tr>
                              </table>
                          </center>
                         </span>
                   
                <?php } ?>
          </td>
          <td>
                <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                <!-- MinorityGames -->
                <ins class="adsbygoogle"
                     style="display:inline-block;width:120px;height:600px"
                     data-ad-client="ca-pub-3114329450026492"
                     data-ad-slot="2005017060"></ins>
                <script>
                (adsbygoogle = window.adsbygoogle || []).push({});
                </script>
          </td>
       </tr>
    </table>
</div>   
</body>
</html>