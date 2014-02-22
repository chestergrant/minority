<?php
set_time_limit(0);
include_once "common.php";
include_once '../class/game_manager.php';
$gamemanager = new game_manager($db);
$start_time  = time();
if(!$gamemanager->activeGames()){
        $gamemanager->createGame();
}
while($start_time+3600>time()+5){
//while(true){
    $left = $gamemanager->getTimeLeftInState();
    echo $left."<---";
    if($left <= 0){
        echo "Going for the touch down!!<br>";
        flush();
        echo time()."<br>";
        $gamemanager->createNextState();
        echo time()."<br>";
    }
    echo "Stop me....";
    echo time()."<br>";
    sleep(5);
    echo time()."<br>";
    //exit();
}
?>
