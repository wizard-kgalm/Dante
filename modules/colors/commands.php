<?php
switch($args[1]){
	case "change":
	case "set":
		$color1 = $args[4];
		$color2 = $args[5];
		if(empty($args[2])){
			return $dAmn->say("$from: You must provide a username.",$c);
		}
		if(empty($args[3])){
			if(strtolower($from) !== strtolower($config['bot']['owner'])){
				return $dAmn->say("$from: You must include the dAmn colors password.",$c);
			}
				$dAmn->say("$from: Place dAmnColors password in bot window.",$c);
				print "\nWhat is {$finding}'s dAmnColors password?\n";
				$pass = trim(fgets(STDIN));
		}
		$a = file_get_contents("http://damncolors.nol888.com/login.php?username=".$args[2]."&password=".$args[3]);
		if(empty($a)){
			$b = file_get_contents("http://damncolors.nol888.com/register.php?username=".$args[2]."&password=".$args[3]);
			if(empty($b)){
				return $dAmn->say("$from: No ID returned. Check the username and dAmnColors password, and make sure these are correct.",$c);
			}
			$a = $b;
		}
		if(empty($color1)){
			if(strtolower($from) !== strtolower($config['bot']['owner'])){
				return $dAmn->say("{$from}: You must provide 2 colors (in HEX format, which is 0-9 and A-F) to set the dAmn colors to.",$c);
			}
			$dAmn->say("$from: Place the username color you want in the bot window.",$c);
			print "\nWhat is the user color you want for {$finding}'s dAmnColors?\n";
			$color1 = trim(fgets(STDIN));
		}
		if(empty($color2)){
			if(strtolower($from) !== strtolower($config['bot']['owner'])){
				return $dAmn->say("{$from}: You must provide 2 colors (in HEX format, which is 0-9 and A-F) to set the dAmn colors to.",$c);
			}
			$dAmn->say("$from: Place the text color you want in the bot window.",$c);
			print "\nWhat is the text color you want for {$finding}'s dAmnColors?\n";
			$color2 = trim(fgets(STDIN));
		}
		if($color1[0] === "#"){
			$color1 = substr($color1,1);
		}
		if($color2[0] === "#"){
			$color2 = substr($color2,1);
		}
		$d = file_get_contents("http://damncolors.nol888.com/colorchange.php?username=".$args[2]."&uniqid=".$a."&username_c=".$color1."&text_c=".$color2);
		if(empty($d)){
			$dAmn->say($d,$c);
			return $dAmn->say("{$from}: Either the colors you chose are the same, or you left a color out. It is recommended that you check this users colors before you change either of them. If you only want to change one, just put a different value in for the one you want to change.",$c);
		}
		$dAmn->say("{$from}: $args[2]'s dAmnColors were successfully changed to $color1 and $color2! Refresh your colors to reflect this change.",$c);
		break;
	case "check":
		$args[1] = $args[2];
	default:
		$b = file_get_contents("http://damncolors.nol888.com/ColorList.php");
		$pattern = "/\"([0-9a-zA-Z\-]+)\":\['(#[0-9a-zA-Z]+)','(#[0-9a-zA-Z]+)'\],/";
		$matches = array();
		preg_match_all($pattern, $b, $matches);

		if(!empty($args[1])){
			$checking = FALSE;
			foreach($matches[1] as $num => $cuser){
				if(strtolower($cuser) == strtolower($args[1])){
					$checking = TRUE;
					$dAmn->say("$from: $args[1]'s colors are ".$matches[2][$num]." and ".$matches[3][$num].".",$c);
				}
			}
			if(!$checking){
				$dAmn->say("$from: $args[1] doesn't have dAmn colors.",$c);
			}
		}else
			$dAmn->say("$from: Usage: ".$tr."colors <i>username</i>. This command displays the colors of a specified user if they exist. You may want to update the color list first though, using ".$tr."cupdate.",$c);
		break;
}