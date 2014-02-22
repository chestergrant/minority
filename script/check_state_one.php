<?php
$error = false;
if(!isset($_POST['handle'])){
    $error =  true;
}else{
    $handle = $_POST['handle'];
}
if(!isset($_POST['tweet'])){
    $error =  true;
}else{
    $tweet = $_POST['tweet'];
}
if(!$error){    
    if((strlen($handle)<=0)||(strlen($handle)>25)){
        $error = true;
    }
    if(!$error){
        
        if(preg_match('/[^a-z0-9]/i',$handle)){            
            $error = true;
        }
    }
    if((strlen($tweet)<=0)||(strlen($tweet)>140)){        
        $error = true;
    }
    if(!$error){        
        $stored = $gamemanager->storeUser($handle,$tweet);
        if($stored == true){
            $_SESSION['current_state'] = 2;
            $_SESSION['twitter_handle'] = $handle;
        }
    }
}
?>
