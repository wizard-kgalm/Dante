<?php
if(!$args[1]){
	$us=$from;
} else {
	$us=$args[1];
}
$page=@file_get_contents("http://ws.audioscrobbler.com/1.0/user/{$us}/recenttracks.rss");
if($page){
	if(preg_match("/<title>(.*)<\/title>/",$page,$matches)){
		$t=strip_tags($matches[0]);
		$temp=explode("'",$t);
		$n="<a href=\"http://www.last.fm/user/{$temp[0]}/\">{$temp[0]}</a>";
		$temp[0]="<b><a href=\"http://www.last.fm/user/{$temp[0]}/\">{$temp[0]}</a>'".$temp[1]."</b>";
		$t=$temp[0];		
		unset($temp, $matches);
    }
    preg_match_all("/<item\b[^>]*>(.*)<\/item>/Ums",$page,$matches);
    if($matches[0]){
	    foreach($matches[0] as $key => $data){
		    $datas=explode("\n",$data);	    
		    $datas[1]=strip_tags($datas[1]);
		    $top=explode(" – ",$datas[1]);
		    $datas[1]=str_replace($top[0]." – ","",$datas[1]);
		    $datas[2]=str_replace("<link>","<a href=\"",str_replace("</link>","\">{$datas[1]}</a>",$datas[2]));
		    $datas[3]=strip_tags($datas[3]);		    
		    $top=$top[0];
		    $datas[5]=str_replace("<description>","<a href=\"",str_replace("</description>","\">{$top}</a>",$datas[5]));
		    if($key==0){
			    $mus="<b><i>&middot; <abbr title='{$datas[3]}'>{$datas[2]} by {$datas[5]}</abbr></i></b><br>";
		    } else {
			    $mus.="&middot; <abbr title='{$datas[3]}'>{$datas[2]} by {$datas[5]}</abbr><br>";
		    }
		    unset($datas);
	    }
	    $say=$t."<br><sub>".$mus;
	    $dAmn->say("<abbr title='{$f}'></abbr>".$say,$c);
    } else {
	    $dAmn->say($f. "<b>{$n}</b> has no recently played track list.",$c);
    }
} else {
	$dAmn->say($f. "That user has no profile.",$c);
}
?>