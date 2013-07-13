<?php

if($args[1]{0} == "#" || substr($args[1], 0, 5) == "chat:" || $args[1]{0} == "@" || substr($args[1], 0, 6) == "pchat:"){
		$room = generateChatName($args[1]);
		$textToSay = $argsE[2];
		if(debug(false,5)){
			echo "-> TEXTA: "; var_dump($textToSayA);
			echo "-> ROOM: " . $room . "\n-> TEXT: " . $textToSay . "\n";
		}
		
		$textt= preg_replace("/[!@#$.?%(<>)^&*0-9]/","",$textToSay);
		$a = explode(" ",$textt); 
		foreach($a as $nothing => $word){ 
			$ty = strlen(($word)); $hm = (($ty)-1); 
			$mh = str_split($word); 
			$r = $mh[0]; 
			$t = $mh[$hm]; 
			unset($mh[0]); 
			unset($mh[$hm]); 
			$say = implode("",$mh); 
			$text.= $r."".str_shuffle($say)."".$t." ";
			} 
		$dAmn->say($text,$room);		
	} else {
			$ar = preg_replace("/[!@#$.?%(<>)^&*0-9]/","",$argsF);
			$a = explode(" ",$ar); 
			foreach($a as $nothing => $word){ 
				$ty = strlen(($word)); $hm = (($ty)-1);  
				$mh = str_split($word); 
				$r = $mh[0]; $t = $mh[$hm]; 
				unset($mh[0]); 
				unset($mh[$hm]); 
				$say = implode("",$mh); 
				$text.= $r."".str_shuffle($say)."".$t." ";
				} 
	$dAmn->say($text,$c);
	}
?>