<?php

require_once "config.php";
require_once "Snoopy/Snoopy.class.php";

$snoopy = new Snoopy;
$snoopy->agent = "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)";
//$snoopy->referer = "";
$snoopy->rawheaders["Pragma"] = "no-cache";
$snoopy->maxredirs = 3;
$snoopy->offsiteok = false;
$snoopy->expandlinks = false;


$submit_url = "http://series.ly/scripts/login/login.php";

$submit_vars["lg_login"] = SERIESLY_USER;
$submit_vars["lg_pass"] = SERIESLY_PASSWORD;
$submit_vars["recordar"] = "1";
$submit_vars["paso1ok"] = "entrar";
$snoopy->submit($submit_url,$submit_vars);

$snoopy->fetch("http://series.ly/my-series/");
if(preg_match('|var mediaList = \[([^\]]+)\]|', $snoopy->results, $match)) {
    $shows = json_decode("[".$match[1]."]", true);
    print_r($shows);
}






?>