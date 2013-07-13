<?php
global $echo;
if(sizeof($args) < 2){ 
	$dAmn->say('Echoes what\'s said in one channel to another - if only one channel is specified, it is assumed the current channel is the destination channel (ie the channel where the echo is displayed)<br>echochan on [channel1] [channel2] - repeat what people say from channel1 to channel2<br>echochan off [channel1] [channel2] - turns off the echo from channel1 to channel2<br>sechochan del [channel] [channel] - deletes the specified echo<br>echochan list - lists all active echoes<br>echochan clearall - clears all echoes', $c); 
	return; 
}

switch($args[1]) {
	case 'add':
	case 'on':
		if(sizeof($args) < 3 || sizeof($args) > 4) {
			$dAmn->say($f."Usage: echochan on [channel1] [channel2]",$c);
		} else {
			$chan1 = strtolower($args[2]);
			if(sizeof($args) == 4) {
				$chan2 = strtolower($args[3]);
			} else {
				$chan2 = strtolower($c);
			}
			if(strpos($chan1, '#') === false) {
				$chan1 = '#'.$chan1;
			}
			if(strpos($chan2, '#') === false) {
				$chan2 = '#'.$chan2;
			}
			if($config['techo'][$chan1]) {
				if(isset($config['techo'][$chan1][$chan2])) {
					if($config['techo'][$chan1][$chan2] == true) {
						$dAmn->say($f."echo $chan1 => $chan2 already exists and is turned on",$c);
					} else {
						$config['techo'][$chan1][$chan2] = TRUE;
						save_config('techo');
						$dAmn->say($f."echo $chan1 => $chan2 turned on",$c);
					}
				} else {
					$config['techo'][$chan1][$chan2] = true;
					$dAmn->say($f."echo $chan1 => $chan2 created",$c);
				}
			} else {
				$config['techo'][$chan1][$chan2] = true;
				$dAmn->say($f."echo $chan1 => $chan2 created",$c);
			}
			save_config('techo');
		}
		break;
	case 'del':
	case 'rem':
	case 'remove':
	case 'delete':
		if(sizeof($args) > 4 || sizeof($args) < 3) {
			$dAmn->say($f."Usage: echochan del [channel1] [channel2]",$c);
		} else {
			$chan1 = strtolower($args[2]);
			if(sizeof($args) == 4) {
				$chan2 = strtolower($args[3]);
			} else {
				$chan2 = strtolower($c);
			}
			if(strpos($chan1, '#') === false) {
				$chan1 = '#'.$chan1;
			}
			if(strpos($chan2, '#') === false) {
				$chan2 = '#'.$chan2;
			}
			if(isset($config['techo'][$chan1])) {
				if(!isset($config['techo'][$chan1][$chan2])) {
					$dAmn->say($f."echo $chan1 => $chan2 does not exist",$c);
				} else {
					unset($config['techo'][$chan1][$chan2]);
					if(sizeof($config['techo'][$chan1]) < 1) {
						unset($config['techo'][$chan1]);
					}
					$dAmn->say($f."echo $chan1 => $chan2 deleted",$c);
				}
				save_config('techo');
			} else {
				$dAmn->say($f."echo $chan1 => $chan2 does not exist",$c);
			}
		}
		break;
	case 'off':
		if(sizeof($args) > 4 || sizeof($args) < 3) {
			$dAmn->say($f."Usage: echochan off [channel1] [channel2]",$c);
		} else {
			$chan1 = strtolower($args[2]);
			if(sizeof($args) == 4) {
				$chan2 = strtolower($args[3]);
			} else {
				$chan2 = strtolower($c);
			}
			if(strpos($chan1, '#') === false) {
				$chan1 = '#'.$chan1;
			}
			if(strpos($chan2, '#') === false) {
				$chan2 = '#'.$chan2;
			}
			if(isset($config['techo'][$chan1])) {
				if(!isset($config['techo'][$chan1][$chan2])) {
					$dAmn->say($f."echo $chan1 => $chan2 does not exist",$c);
				} else {
					$config['techo'][$chan1][$chan2] = false;
					$dAmn->say($f."echo $chan1 => $chan2 turned off",$c);
				}
				save_config('techo');
			} else {
				$dAmn->say($f."echo $chan1 => $chan2 does not exist",$c);
			}
		}
		break;
	case 'list':
		$chanlist = "";
		foreach($config['techo'] as $k => $v) {
			foreach($v as $ink => $inv) {//innnerkey innerval
				$chanlist.= "$k => $ink; ".(($inv)?"on":"off")."\n";
			}
		}
		if(strlen($chanlist)===0) {
			$dAmn->say($f."no echoes set",$c);
		} else {
			$dAmn->say($f."<b>Echoes</b><br><sub>$chanlist</sub>",$c);
		}
		break;
	case 'clearall': 
		unset($config['techo']);
		$config['techo'] = array();
		$dAmn->say($f."echoes cleared",$c);
		save_config('techo');
		break;
	default:
		$dAmn->say($f."Usage:<br>echochan on [channel1] [channel2] - repeat what people say from channel1 to channel2<br>echochan off [channel1] [channel2] - turns off the echo from channel1 to channel2<br>sechochan del [channel] [channel] - deletes the specified echo<br>echochan list - lists all active echoes<br>echochan clearall - clears all echoes", $c); 
}
?>