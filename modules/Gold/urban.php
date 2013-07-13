<?php
$term = strtolower($args[1]); 
if($term == ''){
		$dAmn->say("$from: <b>".$config['bot']['trigger']."urbandict [term]</b>",$c);
}else{
		if(((is_numeric($args[1])) && (is_numeric($args[2]))) || ((is_numeric($args[1])) && ((!is_numeric($args[2])) && (!empty($args[2]))))){
				$num = $args[1];
				$term = strtolower($argsE[2]);}
		elseif(!is_numeric($args[1])){
				$num = 1;
				$term = strtolower($argsE[1]); }
		elseif((is_numeric($args[1])) && ($args[2] == "")){
				$num = 1;
				$term = $args[1];}
		if($num > 5){
				$num = 5;}
		else {
				$term = strtolower($term);
				$num = $num;}
				$i = 0;
				$tm = preg_replace("/ /","+",$term);
				$a = file_get_contents("http://www.urbandictionary.com/define.php?term=$tm"); 
				$d = preg_match_all("/<div class='definition'>[^>]*>(.*)<\/div>/Us",$a,$matches);  
				if($num == 1){$nums = array(1); } elseif($num == 2){ $nums = array(1,2); } elseif($num == 3){ $nums = array(1,2,3); } elseif($num == 4){ $nums = array(1,2,3,4); } elseif($num == 5){ $nums = array(1,2,3,4,5); }
						foreach($nums as $nothing){
								$som = preg_replace("/<div class='/","<b>".ucfirst($term)."</b> ",$matches[0][$i]); 
								$see = preg_replace("/<\/div>/","",$som); 
								$ro = preg_replace("/'>/",":",$see); 
								$ser = ":magnify:<a href='http://www.urbandictionary.com'> UrbanDictionary</a> Definition For <b>".ucfirst($term)."</b><br/>";
								$tosay = str_replace("</a>"," ", $ro);
								$text = preg_replace("/<a href=\"\/define.php\?(.*)>/"," ",$tosay);
								$that = "definition:";
								$saydis = str_replace(ucfirst($term)." ".$that," ",$text);
								$urban .= "<b>".($i+1).".</b> <sub><b>".strip_tags($saydis)."</sub><br/>";
								$i++;
						}
	$dAmn->say($ser."".$urban,$c);
}
?>