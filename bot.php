<?php
require_once dirname(__FILE__)."/load.php";

$bot = Bot::newInstance();
$snoopy = $bot->snoopy;

$submit_url = "http://series.ly/scripts/login/login.php";

$submit_vars["lg_login"] = SERIESLY_USER;
$submit_vars["lg_pass"] = SERIESLY_PASSWORD;
$submit_vars["recordar"] = "1";
$submit_vars["paso1ok"] = "entrar";
$snoopy->submit($submit_url,$submit_vars);

// GET LIST OF TV SHOWS
$media = Media::newInstance();
$snoopy->fetch("http://series.ly/my-series/");
if(preg_match('|var mediaList = \[([^\]]+)\]|', $snoopy->results, $match)) {
    $shows = json_decode("[".$match[1]."]", true);
    foreach($shows as $show) {
        if($show['pct']==0) { // FOLLOWING
            $snoopy->fetch('http://series.ly/scripts/media/mediaInfo.php?mediaType=1&id_media=64CUPFYTVF');
            //$snoopy->fetch('http://series.ly/scripts/media/mediaInfo.php?mediaType=1&id_media='.$show['id']);
            $raw = json_decode($snoopy->results, true);
            $media->updateShow($raw);
            die;
        }
    }
}

//Episode::newInstance()->download(69870);




?>