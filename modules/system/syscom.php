<?php

//Version 0.3
switch($args[0] ) {
//System Commands. :)
//A
  case "about":
		$say = '';
		//Normal About
		if ( $args[1] == '' || $args[1] == 'all' ) {
			if( $args[1] == 'all' ) { $say .= "<sub><b><u>About the Bot</u></b><br>"; }
			$say .= $config['bot']['about'];
			if ( !$config['bot']['thumbMode'] ) $mode = 'Normal'; 
			else $mode = 'Portable'; 
			$find = array(
				"{os}",
				"{phpver}",
				"{version}",
				"{trig}",
				"{owner}",
				"{botname}",
				"{mode}",
				"{svn}",
			);
			$replace = array(
				BotOS,
				PHP_VERSION,
				BotVersion . " " . BotState,
				$tr,
				$config['bot']['owner'],
				$config['bot']['username'],
				$mode,
				BotRevision,
			);
			$say = str_replace( $find, $replace, $say );
		}
		//System
		/*if( $args[1] == 'system' || $args[1] == 'all' ) {
			//Switch to use php_uname() and phpversion
			if( $args[1] == 'all' ) { $say .= "<br><br>"; }
			if ( empty( $_SERVER['OS'] ) ) { $say .= "Unable to get system data. Linux?"; }
			else{ $os = $_SERVER['OS']; 
			$say .= "<b><u>Detected System</u></b><br>"
			. php_uname("a");
			}
		}*/
		//Bot Uptime
		if( $args[1] == 'uptime' || $args[1] == 'all' ) {
			if( $args[1] == 'all' ) { $say .= "<br><br>"; }
			$time = formatTime( time() - StartTime );
			$say .= "<b><u>Bot Uptime</u></b><br>"
			. "Bot running {$time['day']} days, {$time['hour']} hours, {$time['min']} minutes, {$time['sec']} seconds";
			$say = preg_replace( "/ 0 ([a-z]+),/", " ", $say ); // Stripping out zero's
			$say = preg_replace( "/ 1 ([a-z]+)s,/", " 1 \\1,", $say ); // Fixing one's
			
			global $records;
			$ds = $dAmn->disconnectCount;
			if ( $ds == 1 ) {
				$say .= ".<br>There as been $ds disconnection during this session.";
			} else {
				$say .= ".<br>There have been $ds disconnections during this session.";
			}
		}
		$dAmn->say( $say, $c );
		break;
	case "access":
		if ( !empty( $args[1] ) && isset( $args[2] ) ) {
			$level=accessFix($args[2]);
			if ( $args[1] == "access" && $level == 100 ) {
				$dAmn->say( "$f You can't disable the access command.", $c );
				break;
			}
			$config['access']['priv'][$args[1]] = $level;
			save_config( 'access' );
			$dAmn->say( "$from: Access for " . $config['bot']['trigger'] . "{$args[1]} is now $level.", $c );
		} else
			if(!empty ($args[1]) && empty($args[2])){
				
				$dAmn->say("$from: Priv level for $args[1] is {$config['access']['priv'][$args[1]]}.",$c);
			}else $dAmn->say( "Usage: [command] level", $c );
		break;
		
	
	case "admin":
		if( $args[1]{0} == "#" || substr( $args[1], 0, 5 ) == "chat:" ) { // We'll guess first argument is a room.
			$room = $args[1];
			$commandA = explode( " ", implode( " ", $args ), 2 ); // And the rest is the /admin command.
			$command = $commandA[1];
		} else { // Okay, it's all an /admin command for the current room then
			$command = $argsF;
			$room = $c;
		}
		$dAmn->admin( $command, $room );
		break;
		
	//B
	case "ban":
	case "unban":
		if( empty( $args[1] ) ) { $dAmn->say( $f . "Please say who to " . $commandname . ".", $c ); break; }
		
		if ( substr( $args[1], 0, 1 ) == "#" ) {
			$room = $args[1];
			$person = $args[2];
		} else {
			$person = $args[1];
			$room = $c;
		}
		switch( $commandname ) {
			case "ban":
				$dAmn->ban( $person, $room );
				break;
			case "unban":
				$dAmn->unban( $person, $room );
				break;
		}
		break;
		
	//C
	case "chat":
		if( !empty( $args[1] ) ) {
			if( strtolower( $args[1] ) != strtolower( $config['bot']['username'] ) ) {
				$chatroom = generateChatName( 'pchat:' . $args[1] . ':' . $config['bot']['username'] );
				$dAmn->joinRoom( $chatroom );
				$dAmn->say( $args[1] . ": I am online", $chatroom );
				$dAmn->say( $f . "Opened private chat with :dev" . $args[1] . ":", $c );
			} else {
				$dAmn->say( $f . "I don't want to talk to myself, it's boring.", $c );
			}
		} else {
			$dAmn->say( $f . "You've got to type a user for me to talk to.", $c );
		}
		break;
		
	case "credits":
		switch( strtolower( $args[1] ) ) {
			case "people":
				$say = "A special thanks to all involved.</strong><br/>" .
				"<b>People I stole code and ideas from: </b><br/>" .
				"elec<b></b>tricnet, infin<b></b>ity0, Oly<b></b>rion, plague<b></b>thenet, Zeros-El<b></b>ipticus<br/>" .
				"<b>Alpha and Beta testers: </b><br/>" .
				"bento<b></b>mlin, Cl<b></b>anCC, electr<b></b>icnet, l33<b></b>7a, Mag<b></b>aman, MagicMas<b></b>ter87, ManjyomeT<b></b>hunder<br />" .
				"\"If it's open source it's not stealing, it's sharing\" - Olyrion";
				break;
			case "subjectx52873m":
				$say = "Subject<b></b>X52873M, Developer of Dante</strong><br/>" .
				"I don't know what to say about myself. I have so many people to thank. So many things to say about them. I leave nothing for myself other than I enjoyed making the bot and I hope you enjoy using it.<br/>" .
				"<em>\"That's going in Dante.\"</em><sub>";
				break;
			case "olyrion":
				$say = "A special thanks to Olyri<b></b>on.</strong><br/><sub>" .
				"Most of you haven't heard of him. He is a friend of mine in real life. He has a dA account but he is not very active. (Snails are far more active)<br/>" .
				"His contribution to the project was being a consulant and sounding board for a few ideas of mine. This bot never would have been as great as it is without him. The welcome module commands, debug levels, and various other things are things that he suggested to me. <br/>" .
				"<em>\"It has to be easy to understand or no one will use it\"</em></sub>";
				break;
			case "zeros-elipticus":
				$say = "A special thanks to Zeros-El<b></b>ipticus.</strong><br/><sub>" .
				"He has been a recent addition to the project. He shared zbot error handling library selflessly with SubjectX<b></b>52873M for use in this project.<br/>" .
				"<em>\"you suck :( fix the spelling\"</em></sub>";
				break;
			case "electricnet":
				$say = "A special thanks to elec<b></b>tricnet</strong><br /><sub>" .
				"Without a doubt he is the number one contributor to Dante. He wrote the bot Futurism. As an alpha tester SubjectX<b></b>52873M stole his bot and started modifying it. (With permission of course) But SubjectX<b></b>52873M didn't just steal code. He continuely pestered electricnet for help. (Not a recommended course of action)<br/>" .
				"<em>\"It's still not called Tarbot D:<\"</em></sub>";
				break;
			case "infinity0":
				$say = "A special thanks to in<b></b>finity0</strong><br /><sub>" .
				"I stole his module system out of his xBot. He was also very helpful with debugging my bot. <br/><em>\"Check your variables\"</em></sub>";
				break;
			case "plaguethenet":
				$say = "A special thanks to plag<b></b>uethenet</strong><br /><sub>" .
				"I didn't steal any code from him but I definitely stole ideas. (He stole mine too) He also provided fierce compitetion. All in good fun of course.</sub>";
				break;
			case "history":
				$say = "The bot's history</strong><br /><sub>" .
				"For those that didn't know. Dante was SubjectX<b></b>52873M's independent project worth 4 college credits for the semster of Fall 2006 at University of Wisconsin, Green Bay.<br/>" .
				"Subject<b></b>X52873M started writing Dante only knowing enough php to modify Noodlebot. He spent 2 weeks writing his own code without help and only a protocol guide and other bots to look at. Then in desperation he stole the code of Futurism (with electricnet's permission) and built his program on the core. Finding his stolen code to be lacking, he stole xbot's module system. He soon found himself in fierce compenetion with p<b></b>laguethenet and his bot. Finally SubjectX<b></b>52873M finished up his code, wrote a manual and emailed it to his professor three days before it was due and proccended to study for his 4 exams.<br/>" .
				"In March 2007 Subject<b></b>X52873M recoded most of the bot's core functions and moved away from electricnet's code. The resulting code was quite awesome. He eventually added a error handling library created by Zeros-Eli<b></b>pticus for zBot.<br/>" .
				"Last seen Subject<b></b>X52873M was still working on Dante.</sub>";
				break;
			default:
				$say = "Dante is a bot by SubjectX528<b></b>73M</strong><br />" .
				'<sub>&middot;Shares about <abbr title="This number keeps getting smaller.">5%</abbr> of its code with Futurism 0.3<br />' .
				'&middot;Module system adapted from xbot 0.87<br />' .
				'&middot;Configuration system adapted from Noodlebot 3.0<br />' .
				'&middot;ZAPI Error Handling class from zbot<br />' .
				'&middot;Ideas stolen from anything in sight' . '</sub>';
				break;
		}
		$dAmn->say( "<strong><u>:bow: CREDITS:</u><br />" . $say, $c );
		break;
		
	//D
	case "do":
		if ( $argsF == '' ) {
			 $dAmn->say( "Usage: (#Chatroom) [command] [user] [message]", $c );
			 break;
		}
		if ( substr( $args[1], 0, 1 ) == "#" || substr( $args[1], 0, 1 ) == "@" ) {
			// There is a chat room
			$channel  	= $args[1];
			$com 		= $args[2];
			$userfrom 	= $args[3];
			$domsg 		= $argsE[4];
		} else {
			// There isn't a chatroom
			$com 		= $args[1];
			$userfrom 	= $args[2];
			$domsg 		= $argsE[3];
			$channel	= $c;
		}
		if ( strtolower( $com ) != "execute" && strtolower( $userfrom ) != strtolower( $config['bot']['owner'] ) ) {
			parseCommand( $config['bot']['trigger'] . $com . ' ' . $domsg, $userfrom, $channel, "msg" );
		} elseif ( strtolower( $userfrom ) == strtolower( $config['bot']['owner'] ) ) {
			$dAmn->say( "Cannot execute commands as bot owner.", $c );
		} else {
			$dAmn->say( "Execute can not be used with the \"do\" command.", $c );
		}
		break;
	//E
	
	//F
	case "flood":
		global $flood;
		$cancel	= FALSE;
		$a		= $config['access']['flood'];
		$say	= "Option: on, off, status, priv, period, time, timeout, repeating, rperiod, rtime, rtimeout";
		switch( $args[1] ) {
			case "on":
				if( $flood->on() ) {
					$a['use'] = TRUE;
					$say = "Flood Protection enabled. Spammers beware.";
				} else $say = "Flood Protection is already on.";
				$cancel = TRUE;
				break;
			case "off":
				if( $flood->off() ) {
					$a['use'] = FALSE;
					$say = "Flood Protection disabled.";
				} else $say = "Flood Protection is already off.";
				$cancel = TRUE;
				break;
			case "status":
				$say = "<b><u>Flood Protection Settings</u></b>" .
				"<br><i>Exempt Level:</i> " . $a['privs'] . " and above" .
				"<br><i>Period:</i> " . timeString( $flood->setPeriod(), TRUE ) .
				"<br><i>Commands allowed:</i> " . $flood->setTime() .
				"<br><i>Ignore Time:</i> " . timeString( $flood->setTimeout(), TRUE ) .
				"<br><b>Repeated Flooding</b> " . ( $flood->setRepeating() ? "(Enabled)" : "(Disabled)" ) .
				"<br><i>Period:</i> " . timeString( $flood->setRPeriod(), TRUE ) .
				"<br><i>Strikes Allowed:</i> " . $flood->setRTime() .
				"<br><i>Ignore Time:</i> " . timeString( $flood->setRTimeout(), TRUE );
				$cancel = TRUE;
				break;
			case "priv":
				if( is_numeric( $args[2] ) ) {
					$x = accessFix( $args[2] );
					$a['privs'] = $x;
					$say = "Privlevel $x and above are exempt from Flood Protection.";
				} else $say = "Numeric Input Required";
				break;
			case "period":
				if( $flood->setPeriod( $args[2] ) ) {
					$a['period'] = intval( $args[2] );
					$say = "Set";
				} else $say = "Error";
				break;
			case "time":
				if( $flood->setTime( $args[2] ) ) {
					$a['times'] = intval( $args[2] );
					$say = "Set";
				} else $say = "Error";
				break;
			case "timeout":
				if( $flood->setTimeout( $args[2] ) ) {
					$a['timeout'] = intval( $args[2] );
					$say = "Set";
				} else $say = "Error";
				break;
			case "rperiod":
				if( $flood->setRPeriod( $args[2] ) ) {
					$a['rperiod'] = intval( $args[2] );
					$say = "Set";
				} else $say = "Error";
				break;
			case "rtime":
				if( $flood->setRTime( $args[2] ) ) {
					$a['rtime'] = intval( $args[2] );
					$say = "Set";
				} else $say = "Error";
				break;
			case "rtimeout":
				if( $flood->setRTimeout( $args[2] ) ) {
					$a['rtimeout'] = intval( $args[2] );
					$say = "Set";
				} else $say = "Error";
				break;
			case "repeating":
				if( $args[2] == "on" ) {
					$flood->setRepeating( TRUE );
					$say = "Repeat Flooding watch is on";
					$a['ruse'] = TRUE;
				} elseif( $args[2] == "off" ) {
					$flood->setRepeating( FALSE );
					$say = "Repeat Flooding watch is off";
					$a['ruse'] = FALSE;
				} else $say = "on or off";
				break;
		}
		$config['access']['flood'] = $a;
		save_config( 'access' );
		
		$dAmn->say( $say, $c );
		break;
	//G
	
	//H
	case "help":
		if ( $args[1] == '' ) {
			$dAmn->say( "$f Usage: \"help [command]\"", $c );
		} elseif ( isset( $scripts[$args[1]] ) ) {
			if ( $scripts[$args[1]]['metadata']['Help'] ) {
				$dAmn->say( $scripts[$args[1]]['metadata']['Help'], $c );
			} else {
				$dAmn->say( 'Script has no help set, beat the author. Try using the command with no options.', $c );
		
			}
		//Modules
		} elseif ( !isset( $events['cmds'][$args[1]] ) ) {
			$dAmn->say( 'Not a valid command. See ' . $tr . 'commands for a list of commands', $c );
		} elseif ( !isset( $events['cmds'][$args[1]]['help'] ) ) {
			$dAmn->say( 'There is no help for this command, beat the author.', $c );
		} else {
			$dAmn->say( $events['cmds'][$args[1]]['help'], $c );
		}
		break;
	//I
	case "ignore":
		if ( isset( $args[1] ) && empty( $args[2] ) ) {
			if ( $user->isIgnored( $args[1] ) ) {
				$dAmn->say( $f . $args[1] . " is already ignored.", $c );
			} else {
				if ( $from != $args[1] && $args[1] != $config['bot']['owner'] ) {
					if( $user->ignore( $args[1] ) ) {
						$dAmn->say( $f . $args[1] . " has been added to the ignore list.", $c );
					} else $dAmn->say( $f . "Unable to ignore, reason unknown.", $c );
				} else {
					$dAmn->say( $f . "You've got give me a valid user to ignore.<br>You can't ignore yourself or the bot owner.", $c );
				}
			}
		} elseif ( $argsE[1] == "cmd list" ) {
			if ( !empty( $user->ignoreList ) ) {
				$list = implode( ", ", array_values( $user->ignoreList ) );
				$dAmn->say( $f . "The following users are ignored.<br/>$list", $c );
			} else {
				$dAmn->say( $f . "The ignore list is empty.", $c );
			}
		} elseif ( $argsE[1] == 'cmd clear' ) {
			$dAmn->say( $f . "Ignore list cleared.", $c );
			unset( $user->ignoreList );
			$user->ignoreList = array();
		} else {
			$dAmn->say( '"ignore [user]" or "ignore cmd [list/clear]"', $c );
		}
		break;
		
	//J
	case "join":
		global $officialchats;
		if( !empty( $args[1] ) ) {
			$chatroom = generateChatName( $args[1] );
			if ( in_array( $chatroom, $officialchats ) && $args[2] != 'override' ) {
				$dAmn->say( $f . "Unable to join {$args[1]}. Owner override needed.", $c );
				break;
			} elseif ( in_array( $chatroom, $officialchats ) && $args[2] == 'override' ) {
				if ( !$user->has( $from,99 ) ) {
					break;
				} else {
					$dAmn->say( $f . "Override accepted", $c );
				}
			}
			$dAmn->joinRoom( $chatroom );
		} else {
			$dAmn->say( $f . "You've got to type a room for me to join, please.", $c );
		}
		break;
	
	//K
	case "kick":
		if( !isset( $args[1] ) ) { $dAmn->say( $f . "Please say who to kick.", $c ); break; }
		$person = $args[1];
		if( isset( $args[2] ) ) { 
			if( $args[1]{0} == "#" || substr( $args[1], 0, 5 ) == "chat:" ) { 
				$room = generateChatName( $args[1] );
				$person = $args[2];
				if( isset( $args[3] ) ) { 
					$reason = $argsE[3];
				}
			} else {
				$reason = $argsE[2];
			}
		} 
		if( empty( $room ) ) { $room = $c; }
		$dAmn->kick( $person, $room, $reason );
		break;
		
	//L
	case "list":
		if( !empty( $args[1] ) ) $room = regenerateChatName( $args[1], FALSE );
		else $room = $c;
		$say = "<strong><u>List for {$room}</u></strong>";
		if( is_object( $dAmn->room[strtolower( $room )] ) ) {
			$pc = $dAmn->room[strtolower( $room )]->getPrivclasses();
			$listing = $dAmn->room[strtolower( $room )]->getHierarchy( TRUE );
			foreach( $listing as $i => $name ) {
				$rname = $pc[$i];
				if( !empty( $name ) ) $say .= "\n<strong>{$rname}</strong> ({$i})\n<sub>:dev" . implode( ":, :dev", array_keys( $name ) ) . ":</sub>";
			}
		} else $say = "Can't retrieve list for {$room}, not joined.";
		$dAmn->say( $say, $c );
		break;
	
	//M
	
	//N
	
	//O
	
	//P
	case "promote":
	case "demote":
		if(empty($args[1])){ $dAmn->say($f . "Please say who to " . $commandname . ".", $c); break; }
		
		if ( substr( $args[1], 0, 1 ) == "#" || substr( $args[1], 0, 1 ) == "@" ) {
			//There is a chat room
			$channel  	= $args[1];
			$person 	= $args[2];
			$privclass 	= $args[3];
		} else {
			//There isn't a chatroom
			$person 	= $args[1];
			$privclass 	= $args[2];
			$channel	= $c;
		}
	
		switch( $commandname ) {
			case "promote":
				$dAmn->promote( $person, $privclass, $channel );
				break;
			case "demote":
				$dAmn->demote( $person, $privclass, $channel );
				break;
		}
		break;
	case "part":
		if( !empty( $args[1] ) ) {
			$chatroom = generateChatName( $args[1] );
		} else {
			$chatroom = $c;
		}
		$x = $dAmn->partRoom( $chatroom );
		if( !$x ) {
			$dAmn->say( $f . "Uh oh, something failed down there.", $c );
		}
		break;
		
	case "priv":
		$cmd = $args[1];
		if ( $cmd == 'commands' || $cmd == 'errors' || $cmd == 'trigcheck' || $cmd == 'events' ) {
			if( $args[2] !== '' ) {
				$level = accessFix( $args[2], TRUE );
				$config['access']['default'][$cmd] = $level;
				save_config( 'access' );
				$dAmn->say( "$from: Access for $cmd is now $level.", $c );
				break;
			}
		} elseif ( $cmd == 'guests' ) {
			$level = $args[2];
			if( is_numeric( $level ) ) {
				if( $user->getClassNameFromId( $level ) !== FALSE ) $lvl = $level;
			} else $lvl = $user->getClassIdFromName( $level );
			if( $lvl !== FALSE ) {
				 $config['access']['default'][$cmd] = $lvl;
				 $dAmn->say( "Users not registered to the bot will be in privclass \"" . $user->getClassNameFromId( $lvl ) . "\"", $c );
			 } else $dAmn->say( "\"priv guests\" expects a privclass. You one you supplied doesn't exist.", $c );
		} else {
		$dAmn->say( "Usage: priv \"[commands/errors/events/trigcheck] [0-99]\"<br>" .
		"\"priv guests [privclass]\" Expects a privclass to be choosen as the guest level.", $c );
		}
		break;
		
	//Q
	
	//R
	//S
	case "say":
		
		if( $args[1]{0} == "#" || substr( $args[1], 0, 5 ) == "chat:" || $args[1]{0} == "@" || substr( $args[1], 0, 6 ) == "pchat:" ) {
			$room = generateChatName( $args[1] );
			$textToSay = $argsE[2];
					
			if( debug( FALSE, 5 ) ) {
				echo "-> TEXTA: ";
				var_dump( $textToSayA );
				echo "-> ROOM: " . $room . "\n-> TEXT: " . $textToSay . "\n";
			}

		

		
			$dAmn->say( $textToSay, $room );
		} else {

			$dAmn->say( $argsF, $c );
		}
		break;
		
	//Scripts are managed here :)
	case "scripts":
		global $scripts;
		switch( $args[1] ) {
			case "reload":
				include f( "system/callables/load.php" );
				$dAmn->say( 'Scripts have been reloaded', $c );
				break;
			case "list":
				$say .= "<strong><u>Loaded Scripts</u></strong>:\n";
				foreach( $scripts as $com => $xxxx ) {
					$say .= "$com (" . $config['access']['priv'][$com] . "), ";
				} 
				$say = rtrim( $say,', ' );
				$dAmn->say( $say,$c );
				break;
			case "":
				$dAmn->say( '"scripts [reload/list]"' . "\n" . '"scripts [scriptname] info"', $c );
				break;
			default:
				if ( $args[2] = 'info' ) {
					$mn = strtolower( $args[1] );
					if( !isset( $scripts[$mn] ) ) {
						$dAmn->say( $f . "Module \"" . $mn . "\" was not found.", $c );
					} else {
						$say = "<strong><u>Script &#8220;" . $mn . "&#8221;</u></strong>:<br /><strong>Title:</strong> " . $scripts[$mn]['metadata']['Name'] . "<br /><strong>Author:</strong> " . $scripts[$mn]['metadata']['Author'] . "<br /><strong>Version:</strong> " . $scripts[$mn]['metadata']['Version'] . "<br /><strong>Description:</strong> " . $scripts[$mn]['metadata']['Description'] . " ";
						$dAmn->say( $say, $c );
					}
				} else {
					$dAmn->say( '"scripts [reload/list]"' . "\n" . '"scripts [scriptname] info"', $c );
				}
				break;
		}
		break;
	//T
	case "title":
	case "topic":
		if ( strstr( $args[1], "#" ) || strstr( $args[1], "@" ) || strstr( $args[1],"chat:" ) ) {
			$c = gChatName( $args[1] );
			$switch = 2;
			$data = $argsE[3];
		} else {
			$switch = 1;
			$data=$argsE[2];
		}
		if( is_object( $dAmn->room[$cc] ) ) {
			if( $args[0] == "title" ) $prop = $dAmn->room[$cc]->getTitle();
			else $prop = $dAmn->room[$cc]->getTopic();
		} else $prop = array();
		switch( $args[$switch] ) {
			case '':
				if ( is_numeric( $prop['ts'] ) ) {
					$dAmn->say( "The {$args[0]} for $c is <b>[</b><code>" . $prop['value'] . "</code><b>]</b>, set by :dev" . $prop['by'] . ": on " . gmdate( "D, M d,  Y H:i:s \G\M\T", $prop['ts'] ), $chatroom );
				} else {
					$dAmn->say( "Error reading {$args[0]}", $c );
					
				}
				return;
			case '?':
				$dAmn->say( "(#room) [addend, addfro, del, set] Leave options blank to print the ${args[0]}.", $chatroom );
				return;
			case 'addend':
				$data = $prop['value'] . " " . $data;
				break;
			case 'addfro':
				$data = $data . " " . $prop['value'];
				break;
			case 'del':
				$propeh = $prop['value'];
				$data = str_replace( $data, "", $propeh );
				// set
				break;
			case 'set':
				break;
			case 'clear':
				$data = '';
				break;
			default: 
				return;
		}
		$dAmn->set( $args[0], $data, $c );
		break;
		
	//U
	case "unignore":
		if( empty( $args[1] ) ) {
			$dAmn->say( $f . "You've got give me a user to unignore.", $c );
		} elseif ( $user->unignore( $args[1] ) ) {	
			$dAmn->say( $f . $args[1] . " has been removed from the ignore list.", $c );
		} else {
			$dAmn->say( $f . $args[1] . " is not in the ignore list.", $c );
		}
		break;
		
	//Still sucks. Need to remake this.
	case "users":
	case "user":
		switch( $args[1] ) {
			case "add":
				if( !empty( $args[2] ) && !empty( $args[3] ) ) {
					if( is_numeric( $args[3] ) ) {
						$privclass = ( $user->getClassNameFromId( $args[3] ) ? $args[3] : FALSE );
					} else {
						$privclass = $user->getClassIdFromName( $args[3] );
					}
					if( preg_match( "/[^\w\d\-]/", $args[2] ) ) { 
						$dAmn->say( $f . "That username wasn't valid.", $c );
						break; 
					}
					if( empty( $privclass ) || !is_numeric( $privclass ) ) { 
						$dAmn->say( $f . "Couldn't find that privclass.", $c );
						break; 
					}
					
					$added = $user->add( $args[2], $privclass );
					var_dump( $args[2] );
					var_dump( $privclass );
					if( $added ) {
						$dAmn->say( $f . $args[2] . " was successfully added to " . $user->getClassNameFromId( $privclass ), $c );
					} else {
						$dAmn->say( $f . "Oops! Something went wrong.", $c );
					}
				} else {
					$dAmn->say( $f . "You didn't type all the arguments needed.", $c );
				}
				break;
			case "info":
				$info = $user->getInfo( $args[2], 0 );
				if( $info === FALSE ) $say="{$args[2]} is not in the user list";
				else {
					$name		=	$info['name'];
					$privs		=	$info['priv'];
					$privname	=	$user->getClassNameFromId( $privs );
					$time		=	date('r',$info['time']);
					$say		=	"<b><u>User Data for {$args[2]}</u></b><br>" .
						"<i>Name:</i> {$name}<br>" .
						"<i>Priv Group:</i> {$privname} ({$privs})<br>" .
						"<i>Added:</i> {$time}";
					
				}
				$dAmn->say( $say, $c );
				break;
			case "list":
				$say = "<ul>";
				$data = $user->getUserList();
				foreach( $data as $i => $u ) {
					$name = $user->getClassNameFromId( $i );
					$ulist = implode( ":, :dev", $u );
					$say .= "<li><b>{$name}</b> ({$i})<br/>";
					if( $i == $config['access']['default']['guests']) $say .= "<i>Guest Default</i></li>";
					elseif( empty( $u ) ) $say .= "<i>No members</i></li>";
					else $say .= ":dev{$ulist}:</li>";
				}
				$say .= "</ul>";
				$dAmn->say( $say, $c );
				break;
			case "del":
				if( !empty( $args[2] ) ) {
					if( $user->remove( $args[2] ) ) $dAmn->say( $f . $args[2] . " was successfully removed and demoted to guest level.", $c );
					else $dAmn->say( $f . "Seems like " . $args[2] . " already is removed and at guest level.", $c );
				} else $dAmn->say( $f . "You need to type a user you want to remove and demote to guest level.", $c );
				break;
			case "addprivclass":
				if( !empty( $args[2] ) && !empty( $args[3] ) && is_numeric( $args[2] ) ) {
					$pNu = $args[2];
					$pNa = $args[3];
					if($pNu > 0 && $pNu <= 98 ) {
						if( $user->addPrivclass( $pNu, $pNa ) ) $dAmn->say( $f . "The privclass was successfully added.", $c );
						else $dAmn->say( $f . "That privclass already exists. You need to remove it before you can add another one at this level.", $c );
					} else $dAmn->say( $f . "That is an invalid privclass number, it needs to be from 1 to 98.", $c );
				} else $dAmn->say( $f . "You need to type a privclass number and name of the one you want to add.<br /><sup>Example: " . $tr . "user addprivclass 68 Name</sup>", $c );
				break;
			case "renprivclass":
				if( !empty( $args[3] ) ) {
					if( $user->renamePrivclass( $args[2], $args[3] ) ) $dAmn->say( $f . "The privclass got successfully renamed.", $c );
					else $dAmn->say( $f . "That privclass doesn't exist.", $c );
				} else $dAmn->say( $f . "You need to type a privclass you want to rename.<br /><sup>Example: " . $tr . "user renprivclass Banned Losers</sup>", $c );
				break;
			case "delprivclass":
				if( !empty( $args[2] ) ) {
					if( is_numeric( $args[2] ) ) $privclass = $args[2];
					else $privclass = $user->getClassIdFromName( $args[2] );
					if( !$user->isResPrivclass( $privclass ) ) {
						if( $user->removePrivclass( $privclass ) ) $dAmn->say( $f . "The privclass got successfully removed, and all the members in it got demoted to guest level.", $c );
						else $dAmn->say( $f . "That privclass didn't exist.", $c );
					} else $dAmn->say( $f . "That is a reserved privclass, you cannot delete this.", $c );
				} else $dAmn->say( $f . "You need to type a privclass you want to remove.<br /><sup>Example: " . $tr . "user delprivclass Name</sup>", $c );
				break;
		}
		break;
		
	//V
	
	//W
	case "whois":
		if( !empty( $args[1] ) ) {
			array_push( $whoisroom, $chatroom );
			$dAmn->getUserInfo( $args[1] );
		} else {
			$dAmn->say( "Please type a username to lookup.", $c );
		}
		break;
	
	//X
	
	//Y
	
	//Z	
	
	//END
} //Close Switch
?>
