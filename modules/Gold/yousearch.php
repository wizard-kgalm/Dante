<?php
if($args[1] == ''){
	$dAmn->say("$from: Must enter a search query",$c);
}else{
	if($args[2] == ''){
		$b = $args[1];
			}
	else{
		$a = explode(' ',$argsF);
		$b = implode($a, '+');
		}
	$search = "http://www.youtube.com/results?search_query=".$b."&search_type=";
	}
	$smex = file_get_contents($search);
	$smexs = substr($smex,20000);
	$shitholoe = preg_match_all("/<a href=\"\/watch\?v=(.*)<\/a>/i",$smex,$matches);
	$ro = str_replace("<a href=\"/watch?v=","",$matches[0]);
	$rro = preg_replace("/title=\"(.*)\"/"," ",$ro);
	$rros = str_replace("</a>","<br/> ",$rro);
	$rs = str_replace("alt=\"video\">"," ",$rros);
	$re = str_replace("\">"," ",$rs);
	$ri = str_replace("src= "," ",$re);
	$ra = str_replace("class= "," ",$ri);
	$r = str_replace("\""," ",$ra);
	$vids = array(1 => "http://www.youtube.com/watch?v=".$r[1], 2 => "http://www.youtube.com/watch?v=".$r[4], 3 => "http://www.youtube.com/watch?v=".$r[8], 4 => "http://www.youtube.com/watch?v=".$r[11], 5 => "http://www.youtube.com/watch?v=".$r[16]);
	foreach($vids as $num => $vi){
	$txt.= "<b>".$num.".</b> ".$vi."";
	}
	$dAmn->say("<b><a href=\"".$search."\"title=\"\"> ".ucfirst($argsF)."</b>:<br/>".$txt,$c);
?>
