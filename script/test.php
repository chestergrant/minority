<?php
session_start();
session_destroy();
include_once "common.php";
include_once '../class/minoritygames.php';
$gamemanager = new minoritygames($db);
$ip ="1.0.0.128";
$link_id = "idkc";
$number = 1;
$url = $gamemanager->getId($ip);
echo $url."<br>";
?>
