<?php
if($args[1] == ""){
	$dAmn->say("Enter video url.",$c); 
}else{
	$a = file_get_contents($args[1]); 
	preg_match_all("/var swfArgs = {\"(.*)\"}/i",$a,$matches); 
	$file = $matches[0][0]; 
	$shits = explode(' ',$file); 
	$shit = str_replace("\""," ",$shits); 
	$fo = str_replace(":","",$shit); 
	$fa = str_replace(",","",$fo);
	$fu = str_replace("}"," ",$fa);
	preg_match("/<title>(.*)<\/title>/i",$a,$lol);  $r = explode(" ",$lol[1]); unset($r[0]); unset($r[1]); $name = implode($r,"%20"); 
	$url = "http://www.youtube.com/get_video?vq=null&video_id=".trim($fu[6])."&l=".trim($fu[8])."&sk=".trim($fu[10])."&fmt_map=&t=".trim($fu[14])."&hl=en&plid=".trim($fu[18])."&t=".trim($fu[14])."&OBT_fname=".$name.".flv";
	$dAmn->say("<B>".implode($r," ").":</b><br/>".trim($url)."<br/><sub>1. Click the link and save the fie whereever yo want.<br/>2. When done rename the file into what ever name you want nd finish it with .flv <br/>3. Play your video. <br/><sub>You'll need <a href=\"http://software-files.download.com/sd/6YMkbjNxz1l1DhULpHH2GTa6gPPt12ZylPU9W2fJIJeaHIqBvfWX0NXUeSZPu8SNN-Hz_AWOc9-4Cmjgub1mblD8umZHatUG/software/10814415/10467081/3/flvplayer_setup.exe?lop=&amp;ptype=1721&amp;ontid=2139&amp;siteId=4&amp;edId=3&amp;spi=0b5c563b731cce2b562625b9fe16ccec&amp;pid=10814415&amp;psid=10467081\">FLV Player</a>.<br/>Enjoy. ;)",$c); 
}
?>