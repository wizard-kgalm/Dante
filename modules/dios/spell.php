<?php
$page=@file_get_contents("http://www.spellcheck.net/cgi-bin/spell.exe?action=CHECKWORD&string=".urlencode($argsF));
if(preg_match("/<font color=#006600><B>\"{$argsF}\" is spelled correctly.<\/B><\/font>/", $page, $matches)){
	$dAmn->say("<abbr title='{$f}'></abbr>\"<b>{$argsF}</b>\" is spelt correctly.", $c);
} else {
	$bo=explode("BLOCKQUOTE>",$page);
	$bo[1]=strip_tags($bo[1]);
	$bo[1]=str_replace("</","",$bo[1]);
	$bo[1]=str_replace("\n",", ",$bo[1]);
    $dAmn->say("<abbr title='{$f}'></abbr>\"<b>{$argsF}</b>\" is spelt incorrectly.<br>Suggested words<br><sub>{$bo[1]}",$c);
} 
?>