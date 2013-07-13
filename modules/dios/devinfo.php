<?php
$args[1]=preg_replace("/[^\w\d\-]/", "", $args[1]);
if($args[1]=="") {
	$who=$from;
} else {
	$who=$args[1];
}
$data=@file_get_contents("http://{$who}.deviantart.com/");
if(preg_match_all("/<li class=\"f\">(.*)<\/li>/Ums", $data, $matches)){
	$info=array();
	foreach($matches[1] as $match)
		if(str_replace("<a ", "", $match)==$match && str_replace("<img ", "", $match)==$match)
			$info[]=preg_replace("/<span (.*)>/", "", preg_replace("/<\/span>/", "", $match));
	$txt=":icon{$who}: :dev{$who}:<sup><br>";
	foreach($info as $d)
		$txt.=$d."<br>";
	$dAmn->say($txt, $c);
} else {
	$dAmn->say("User {$who} does not exist.", $c);
}
?>