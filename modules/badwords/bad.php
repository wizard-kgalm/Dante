<?php
global $dAmn, $config;
if(sizeof($args) < 2) {
	$dAmn->say($f."Please type <b>".$config['bot']['trigger']."badwords ?</b> to see the full help",$c);
} else {
	switch($args[1]) {
		case 'add':
			if(sizeof($args) < 3) {
				$dAmn->say($f."Adds a non-case-sensitive word to the list. Usage: badwords add <i>[channel]</i> [word]",$c);
			} else {
				if(sizeof($args) == 3) {
					$config['badwords']['@all']['lower'][strtolower($args[2])] = strtolower($args[2]);
					save_config('badwords');
					$dAmn->say($f."$args[2] added as a banned word (not case-sensitive) in all channels",$c);
				} else if(sizeof($args) == 4) {
					$chat = strtolower($args[2]);
					if(strpos($chat, '#')===0) {
						$chat = substr($chat, 1);
					}
					$config['badwords']["$chat"]['lower'][strtolower($args[3])] = strtolower($args[3]);
					save_config('badwords');
					$dAmn->say($f."$args[3] added as a banned word (not case-sensitive) in #$chat",$c);
				} else {
					$dAmn->say($f."Adds a non-case-sensitive word to the list. Usage: badwords add <i>[channel]</i> [word]",$c);
				}
			}
			break;
		case 'addcase':
			if(sizeof($args) < 3) {
				$dAmn->say($f."Adds a case-sensitive word to the list. Usage: badwords addcase <i>[channel]</i> [word]",$c);
			} else {
				if(sizeof($args) == 3) {
					$config['badwords']['@all']['upper'][$args[2]] = $args[2];
					save_config('badwords');
					$dAmn->say($f."$args[2] added as a banned word (case-sensitive) in all channels",$c);
				} else if(sizeof($args) == 4) {
					$chat = strtolower($args[2]);
					if(strpos($chat, '#')===0) {
						$chat = substr($chat, 1);
					}
					$config['badwords']["$chat"]['upper'][$args[3]] = $args[3];
					save_config('badwords');
					$dAmn->say($f."$args[3] added as a banned word (case-sensitive) in #$chat",$c);
				} else {
					$dAmn->say($f."Adds a case-sensitive word to the list. Usage: badwords addcase <i>[channel]</i> [word]",$c);
				}
			}
			break;
		case 'del':
			if(sizeof($args) < 3) {
				$dAmn->say($f."Removes a bad word from the case insensitive list. Usage: badwords del <i>[channel]</i> [word]",$c);
			} else {
				if(sizeof($args) == 3) {
					if(isset($config['badwords']['@all']['lower'][strtolower($args[2])])) {
						unset($config['badwords']['@all']['lower'][strtolower($args[2])]);
						save_config('badwords');
						$dAmn->say($f."$args[2] deleted as a banned word (not case-sensitive) in all channels",$c);
					} else {
						$dAmn->say($f."$args[2] was not a global banned word",$c);
					}
				} else if(sizeof($args) == 4) {
					$chat = strtolower($args[2]);
					if(strpos($chat, '#')===0) {
						$chat = substr($chat, 1);
					}
					if(isset($config['badwords']["$chat"]['lower'][strtolower($args[3])])) {
						unset($config['badwords']["$chat"]['lower'][strtolower($args[3])]);
						save_config('badwords');
						$dAmn->say($f."$args[3] deleted as a banned word (not case-sensitive) in #$chat",$c);
					} else {
						$dAmn->say($f."$args[3] was not a banned word in #$chat",$c);
					}
				} else {
					$dAmn->say($f."Deletes a non-case-sensitive word from the list. Usage: badwords del <i>[channel]</i> [word]",$c);
				}
			}
			break;
			case 'delcase':
			if(sizeof($args) < 3) {
				$dAmn->say($f."Removes a bad word from the case sensitive list. Usage: badwords delcase [word]",$c);
			} else {
				if(sizeof($args) == 3) {
					if(isset($config['badwords']['@all']['upper'][$args[2]])) {
						unset($config['badwords']['@all']['upper'][$args[2]]);
						save_config('badwords');
						$dAmn->say($f."$args[2] deleted as a banned word (case-sensitive) in all channels",$c);
					} else {
						$dAmn->say($f."$args[2] was not a global banned word",$c);
					}
				} else if(sizeof($args) == 4) {
					$chat = strtolower($args[2]);
					if(strpos($chat, '#')===0) {
						$chat = substr($chat, 1);
					}
					if(isset($config['badwords']["$chat"]['upper'][$args[3]])) {
						unset($config['badwords']["$chat"]['upper'][$args[3]]);
						save_config('badwords');
						$dAmn->say($f."$args[3] deleted as a banned word (case-sensitive) in #$chat",$c);
					} else {
						$dAmn->say($f."$args[3] was not a banned word in #$chat",$c);
					}
				} else {
					$dAmn->say($f."Deletes a case-sensitive word from the list. Usage: badwords del <i>[channel]</i> [word]",$c);
				}
			}
			break;
		case 'message':
			if(sizeof($args) < 3) {
				$dAmn->say($f."Sets the /kick message, use {word} for the bot to insert the word used. Usage: badwords message <message>",$c);
			} else {
				$config['badwordsettings']['kick'] = $argsE[2];//@todo channel
				save_config('badwordsettings');
				$dAmn->say($f."Message for /kick set",$c);
			}
			break;
		case 'showmessage':
			if(isset($config['badwordsettings']['kick']))
				$dAmn->say($f."The /kick message is: ".$config['badwordsettings']['kick'],$c);
			else
				$dAmn->say($f."No /kick message set",$c);
			break;
		case 'list':
			if(isset($config['badwords'])) {
				$words = "";
				if(isset($config['badwords']['@all'])) {
					if(isset($config['badwords']['@all']['lower'])) {
						$addstring = "";
						foreach($config['badwords']['@all']['lower'] as $val) {
							$addstring.="$val, ";
						}
						if(strlen($addstring) > 0)  {
							$words .= "<u>Non-case sensitive global words</u>\n".substr($addstring,0,-2);
						}
					}
					if(isset($config['badwords']['@all']['upper'])) {
						$addstring = "";
						foreach($config['badwords']['@all']['upper'] as $val) {
							$addstring.="$val, ";
						}
						if(strlen($addstring) > 0)  {
							if(strlen($words) > 0)  {
								$words .= "\n";
							}
							$words .= "<u>Case sensitive global words</u>\n".substr($addstring,0,-2);
						}
					}
				}
				//do channels
				foreach($config['badwords'] as $k => $v) {
					if($k !== '@all') {
						if(isset($v['lower'])) {
							$addstring = "";
							foreach($v['lower'] as $val) {
								$addstring.="$val, ";
							}
							if(strlen($addstring) > 0)  {
								$words .= "<u>Non-case sensitive words for $k</u>\n".substr($addstring,0,-2);
							}
						}
						if(isset($v['upper'])) {
							$addstring = "";
							foreach($v['upper'] as $val) {
								$addstring.="$val, ";
							}
							if(strlen($addstring) > 0)  {
								if(strlen($words) > 0)  {
									$words .= "\n";
								}
								$words .= "<u>Case sensitive words for $k</u>\n".substr($addstring,0,-2);
							}
						}
					}
				}
				//say
				if(strlen($words) > 0) {
					$dAmn->say($f."<br><sub>$words</sub>",$c);
				} else {
					$dAmn->say($f."No words set",$c);
				}
			} else {
				$dAmn->say($f."No words set",$c);
			}
			break;
		case 'clearwords':
			unset($config['badwords']);
			save_config('badwords');
			$dAmn->say($f."banned words cleared",$c);
			break;
		case 'clearmessage':
			unset($config['badwordsettings']['kick']);
			save_config('badwordsettings');
			$dAmn->say($f."kick message cleared",$c);
			break;
		case 'listonword':
			if(sizeof($args) < 3)
				$dAmn->say($f."Sets whether {word} lists one bad word or all, if someone says more than one at once. Usage: badwords listonword [true/false]",$c);
			else {
				if($args[2]=='true') {
					$config['badwordsettings']['listonword'] = true;
					save_config('badwordsettings');
					$dAmn->say($f."{word} will now list all used words",$c);
				} else if($args[2]=='false') {
					$config['badwordsettings']['listonword'] = false;
					save_config('badwordsettings');
					$dAmn->say($f."{word} will now display first bad word used",$c);
				} else
					$dAmn->say($f."Usage: badwords listonword [true/false]",$c);
			}
			break;
		case 'ban':
			if(sizeof($args) < 3) {
				$setting = "";
				if(!isset($config['badwordsettings']['ban'])) $setting = "no ban";
				else {
					if($config['badwordsettings']['ban']===true) $setting = "ban on bad word";
					else if($config['badwordsettings']['ban']===false) $setting = "no ban";
					else $setting = "ban for ".$config['badwordsettings']['ban']." seconds";
				}
				$dAmn->say($f."Sets the bot to ban a user on usage of a swear word, with an optional timer Usage: badwords ban [true/false/[length of time in seconds]]\nCurrent setting is: $setting",$c);
			} else {
				if($args[2]=='true') {
					$config['badwordsettings']['ban'] = true;
					save_config('badwordsettings');
					$dAmn->say($f."Users are now banned on saying a bad word",$c);
				} else if($args[2]=='false') {
					$config['badwordsettings']['ban'] = false;
					save_config('badwordsettings');
					$dAmn->say($f."Users are now not banned on saying a bad word",$c);
				} else if(is_numeric($args[2])) {
					if($args[2] > 0) {
						$config['badwordsettings']['ban'] = $args[2];
						save_config('badwordsettings');
						$dAmn->say($f."Users are now banned for $args[2] seconds on saying a bad word",$c);
					} else {
						$dAmn->say($f."Usage: badwords ban [true/false/[length of time in seconds]]",$c);
					}
				} else {
					$dAmn->say($f."Usage: badwords ban [true/false/[length of time in seconds]]",$c);
				}
			}
			break;
			case 'addpunc':
				if(sizeof($args) == 3) {
					if(strlen($args[2])===1 || $args[2]=='&lt;' || $args[2]=='&gt;' || $args[2]=='&amp;') {
						if(!isset($config['badwordsettings']['delimiters'])) {
							$config['badwordsettings']['delimiters'] = array('?',',','.','!','"',"'");
						}
						if(array_search($args[2],$config['badwordsettings']['delimiters'])===false) {
							$config['badwordsettings']['delimiters'][] = $args[2];
							save_config('badwordsettings');
							$dAmn->say($f."Added <b>$args[2]</b> as punctuation",$c);
						} else {
							$dAmn->say($f."<b>$args[2]</b> is already punctuation",$c);
						}
					} else {
						$dAmn->say($f."Punctuation can only be one character. Usage: badwords addpunc [character]",$c);
					}
				} else {
					$dAmn->say($f."Usage: badwords addpunc [character] ",$c);
				}
				break;
			case 'delpunc':
				if(sizeof($args) == 3) {
					if(strlen($args[2])===1 || $args[2]=='&lt;' || $args[2]=='&gt;' || $args[2]=='&amp;') {
						if(!isset($config['badwordsettings']['delimiters'])) {
							$config['badwordsettings']['delimiters'] = array('?',',','.','!','"',"'");
						}
						if(array_search($args[2],$config['badwordsettings']['delimiters'])===false) {
							$dAmn->say($f."<b>$args[2]</b> was not punctuation",$c);
						} else {
							unset($config['badwordsettings']['delimiters'][array_search($args[2],$config['badwordsettings']['delimiters'])]);
							save_config('badwordsettings');
							$dAmn->say($f."<b>$args[2]</b> removed from punctuation list",$c);
						}
					} else {
						$dAmn->say($f."Punctuation can only be one character. Usage: badwords delpunc [character]",$c);
					}
				} else {
					$dAmn->say($f."Usage: badwords delpunc [character]",$c);
				}
				break;
			case 'listpunc':
				if(!isset($config['badwordsettings']['delimiters'])) {
					$config['badwordsettings']['delimiters'] = array('?',',','.','!','"',"'");
				}
				$listing = "";
				foreach($config['badwordsettings']['delimiters'] as $v) {
					$listing.="$v&nbsp;&nbsp;";
				}
				if(strlen($listing) > 0) {
					$dAmn->say($f."punctuation: $listing",$c);
				} else {
					$dAmn->say($f."There are no punctuation characters",$c);
				}
				break;
		default:
			$dAmn->say($f."Invalid command selection. Please type <b>".$config['bot']['trigger']."badwords ?</b> to see the full help",$c);
	}
}
?>