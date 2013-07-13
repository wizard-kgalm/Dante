<?php
if($argsF=="") {
    return $dAmn->say(" Usage: {$tr}thumb [thumbnumber].",$c);
}
$thumb=$argsF;
$thumb=str_replace(":","", $thumb);
$thumb=str_replace(" ","", $thumb);
$thumb=str_replace("thumb","", $thumb);
$dAmn->say("<i>Retrieving info from deviant's page:thumb6430580:",$c);
$url=@file_get_contents("http://www.deviantart.com/deviation/{$thumb}/");
$acm=explode("<div class=\"",$url);
foreach($acm as $k => $vas){
	if(substr($vas,0,15)=="text block pppt"){
		$acm=str_replace("text block pppt\">","",$vas);
		$acm=str_replace("</div>","",$acm);		
	}
}
if(preg_match("/<title>(.*) on deviantART<\/title>/Ums", $url, $matches)) 
{
     $title=str_replace("<title>","", str_replace(" on deviantART</title>","",$matches[0]));
     $temp=explode("by ",$title);
     $title='<a href="http://www.deviantart.com/deviation/'.$thumb.'/">'.$temp[0].'</a> by :dev'.substr($temp[1],1).':';
     if(preg_match("/<div class=\"details\">(.*)<\/div>/Ums", $url, $matches)) 
        $details=str_replace("<div class=\"details\">","", str_replace("</div>","",$matches[0]));
        $details=explode("<br/>",$details);  
        foreach($details as $n => $vall){
	        if($n>=0 && $n<=11){
		        $details[$n]=strip_tags($details[$n]);
	        } else {
		        unset($details[$n]);
	        }
        }  
        unset($details[8]);
        $details=implode("",$details);
        $details=str_replace("Details","<b>Details</b>",$details);
        $details=str_replace("Downloads","<b>Downloads</b>",$details);
        $details=str_replace("Views","<br/><b>Views</b>",$details);
        $details=str_replace("Full-views","<b>Full-views</b>",$details);
        $details=str_replace("[who?]","<a href=\"http://www.deviantart.com/deviation/{$thumb}/favourites\">[who?]</a>",$details);
        $details=trim($details);
        $acm=strip_tags($acm);
        $acm=trim($acm);
        $details.="<br><b>Artist's Comments</b><br>".$acm;
        $dAmn->say("<abbr title='{$f}'></abbr><b>[:bulletred: <i>Deviation Info on {$title}</i> :bulletred:]</b><br>:thumb{$thumb}:<br> <sub><a href=\"http://www.deviantart.com/view/{$thumb}/#commentbody\"><b>Add a Comment!</b></a><br>{$details}</sub>",$c);
} else $dAmn->say("{$from}: <b><i>Devation does not exist.", $c);
?>