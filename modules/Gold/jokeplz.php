<?php
switch ($args[1]) {
	case '':
			$a = @file_get_contents("http://www.jokes2go.com/cgi-perl/randjoke.cgi?type=j");
			$r = preg_match_all("/<PRE[^>]*>(.*)<\/td>/Us",$a,$matches); 
			$joke = $matches['1'][0]; 
			$dAmn->say("<B><sub>:lol:$joke",$c);
		break;
	case 'about':
			$dAmn->say("$f Script developed by :devgoldenskl:.",$c);
		break;
	case 'submit':
		$dAmn->say("$f To submit your own quote visit. http://www.damnquotes.com/submit.php (dAmnquotes)",$c);
		break;
	}
?>