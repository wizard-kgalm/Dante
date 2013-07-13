<?php
switch ($args[1]) {
	case '':
			$a = @file_get_contents("http://www.damnquotes.com/display.php?id=random");
			$b = preg_match_all("/<td[^>]*>(.*)<\/td>/Us",$a,$matches);
			$t = implode("<br/>",$matches[1]); 
			$you = preg_match("/<!</a>:><->/",$t,$matches); 
			$wsh = $matches['1'][1]; 
			$dAmn->say(":magnify: <B><sub>".substr($wsh,20,-18)."</sub></B>",$c);
		break;
	case 'about':
			$dAmn->say("$f Script developed by :devgoldenskl:.",$c);
		break;
	case 'submit':
			$dAmn->say("$f To submit your own quote visit. http://www.damnquotes.com/submit.php (dAmnquotes)",$c);
		break;
}
?>