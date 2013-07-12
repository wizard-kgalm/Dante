<?php
/**
 * Network and Command related functions.
 *@author SubjectX52873M <SubjectX52873M@gmail.com>
 *@copyright SubjectX52873M 2006
 *@package Dante
 *@license http://www.opensource.org/licenses/gpl-license.php GNU General Public License
 *@version 0.8
 */

/* HAX For Linux signaling. */

/* End HAX */
/**
 *Command failed.
 *@version 0.8
 *@return void
 */
function command_fail( $type, $c, $command, $from ) {
  global $config, $dAmn, $user;
	switch( $type ) {
		case "priv":
		case "privs":
			if( !empty($config['bot']['RestrictedCommandMsg'] ) ) {
				if ( $user->has( $from, $config['access']['default']['errors'] ) ) {
					$txt = $config['bot']['RestrictedCommandMsg'];
					$txt = str_replace("{command}", $command, $txt );
					$txt = str_replace("{from}", $from, $txt );
					$dAmn->say( $txt, $c );
				}
			} else {
				if ( $user->has( $from,$config['access']['default']['errors'] ) )
				$dAmn->say( 'You do not have the privs to access "' . $command . '".', $c );
			}
			break;
		case "badfoo":
			if( !empty($config['bot']['BadCommandMsg'] ) ) {
				if ( $user->has( $from, $config['access']['default']['errors'] ) ) {
					$txt = $config['bot']['BadCommandMsg'];
					$txt = str_replace( "{command}", $command, $txt );
					$txt = str_replace( "{from}", $from, $txt );
					$dAmn->say( $txt, $c );
				}
			} /*else {
			if ($user->has($from,$config['access']['default']['errors']))
			$dAmn->say("\"$command\" is not a valid command.",$c);
			}*/
			intmsg( "Unknown command: \"$command\"." );
			break;
		default:
			if ( $user->has( $from,$config['access']['default']['errors'] ) )
			$dAmn->say( "Something kinky happened to your command. :eyes:", $c );
			intmsg( 'Command Failure: ' . $inputs );
			break;
	}
}

/**
 * Kills the bot
 *@author electricnet
 *@return void
 *@author SubjectX52873M <SubjectX52873M@gmail.com>
 *@version 0.8
 */
function quit( $from, $fromchatroom = "", $s = FALSE ) {
	global $dAmn, $socket;
	if( $fromchatroom ) {
		$f = $from . ": ";
		if( $s ) $dAmn->say( "$f Shutting down.", $fromchatroom );
	}
	//Shutdown gracefully
	$dAmn->send( "disconnect\n" . chr( 0 ) );
	save_config( FALSE, TRUE ); //Force the config to be saved.
	internalSeperator();
	internalMessage( "Bot has shutdown upon request by " . $from . "." );
	@socket_shutdown( $socket );
	@socket_close( $socket );
	exit;
}

/**
 * Processes the recv msg/action events
 *
 * Is called by  dAmn::process()
 *
 * Calls processCommand() if the trigger is noticed.
 *@see dAmn::process()
 *@see processCommand()
 *@return bool True if $message is not empty
 *@author electricnet
 *@author SubjectX52873M <SubjectX52873M@gmail.com>
 *@version 0.6
 */
function processMessage( $message, $from, $chatroom, $msgtype ) {
	global $dAmn, $config, $room,$user, $pingt, $flood;
	//Process if not empty
	if( !empty( $message ) && !empty( $from ) && !empty( $chatroom ) ) {
		//Strip out ignored users, but let owner through.
		if ( $user->isIgnored( $from ) ) {
			return false;
		}
		$bZikes = $config['bzikes']['on'];
		if($bZikes == TRUE){
			$biz = strtolower( substr( $message, 0, strlen( $config['bot']['trigger']."bzikes" ) + 1 ) );
			if(substr( $message, 0, strlen( $config['bot']['trigger']."bzikes" ))!=$config['bot']['trigger']."bzikes"){
				foreach($config['bzikes']['emotes'][1] as $num => $code){
					$message = str_replace($config['bzikes']['emotes'][1][$num], ":thumb".$config['bzikes']['emotes'][2][$num].":", $message);
				}
			}
		}
		$c = $chatroom;
		$f = $from . ": ";
		$namePart = strtolower( substr( $message, 0, strlen( $config['bot']['username'] ) + 1 ) );
		if($namePart == strtolower( $config['bot']['username'] ) . ":" || $namePart == strtolower( $config['bot']['username'] ) . "," ) {
			if ( $user->has( $from, $config['access']['default']['trigcheck'] ) ) {
				if( trim( substr( $message, ( strlen($config['bot']['username']) + 1 ) ) ) == "trigcheck" ) {
					$dAmn->say( $f . $config['bot']['trigger'], $c );
				}
			}
			$message = str_replace($config['bot']['username']  . ": ",$config['bot']['trigger'],$message);
		}
			
		//If it doesn't say what type it is, it's probably an ordinary message.
		if( empty( $msgtype ) ) $msgtype = "msg";
			
		//Ping stuff. Builtin so that if modules fail you still have a ping.
		if( strtolower( $from ) == strtolower( $config['bot']['username'] ) ) {
			if ( $message === "Ping?" && $pingt != 0 ) {
				$ping = microtime( TRUE ) - $pingt;
				$ptmp = round( ( $ping * 1000 ), 2 );
				$dAmn->say( "Pong! ($ptmp ms)", $c );
				$pingt = 0;
			}	//<l337a> Oo! Tell them i said hi!
		} //Strip out the bot to prevent loops.
			
		if( $message == "bots: roll call" && $user->has( $from,99 ) ) {
			$dAmn->say( "$from: <b>" . $config['bot']['username'] . "</b> is online!", $c );
		}


		//Kick it to command parser if it's a command
		if( substr( $message, 0, strlen( $config['bot']['trigger'] ) ) == $config['bot']['trigger'] ) {

			//Flood protection
			$x = FALSE;
			if( !$user->has( $from,$config['access']['flood']['privs'] ) ) $x = $flood->check( $from );
			//false on not flooding, 1 if user is ignored, 2 if user has just been ignored., 3 if user has just been banned.
			switch( $x ) {
				case 1:
					return false;
				case 2:
					//User is ignored. //* SubjectX52873M is ignoring 's' now
					$dAmn->say( "$from: You have been ignored for a short period for spamming the bot.", $chatroom );
					return false;
				case 3:
					$dAmn->say("$from: You have been ignored for an extended period for spamming the bot.", $chatroom );
					return false;
				default:
					parseCommand( $message, $from, $chatroom, $msgtype );
					return true;
			}
		}
	}
}

/**
 * Processes commands
 *
 *This contains the built in commands and the module command handler.
 *@deprecated Some parts
 *@author electricnet
 *@author SubjectX52873M <SubjectX52873M@gmail.com>
 *@version 0.8
 */
function parseCommand( $message, $from, $chatroom, $msgtype ) {
	global	$dAmn, $config, $user, $room, $events, $whoisroom, $latestwhois, $modules, $builtins, $scripts;  //Globals available to commands //Prevent infinite loops
	$cc = strtolower( rChatName( $chatroom ) );
	//Prepare variables: $args, $argsF, $argsE
	$command = substr( $message, strlen( $config['bot']['trigger'] ) );
	if( $command == "" ) {
		return;
	}
	$args = explode( " ", $command );
	$argsE = array();
	foreach( $args as $n => $arg ) {
		$x = explode( " ", $command, $n + 1 );
		$argsE[] = $x[count( $x ) - 1];
	}

	$commandname = strtolower( $args[0] );
	$argsF = $argsE[1];
	unset( $command );

	$c = $chatroom;
	$f = $from . ": ";
	$tr = $config['bot']['trigger'];

	/*
	 * If user has below banned privs they are parsed out.
	 * Banned is more of a restricted class than a "banned" class.
	 * Ignored users are handled in the event system and processmessage()
	 */
	if( !$user->has( $from, 0 ) ) return false;

	//Help system
	if ( $args[1] == "?" ) {
		if ( isset( $builtins[$commandname] ) ) {
			$dAmn->say( 'Command is builtin, there is no help available.', $c );
		} elseif ( isset( $scripts[$commandname] ) ) {
			if ( $scripts[$commandname]['metadata']['Help'] ) {
				$dAmn->say( "$f " . $scripts[$commandname]['metadata']['Help'], $c );
			} else {
				$dAmn->say( 'Script has no help set, beat the author. Try using the command with no options.', $c );
			}
			return;
		} elseif ( !isset( $events['cmds'][$args[0]] ) ) {
			$dAmn->say( 'Not a valid command. See ' . $tr . 'commands for a list of commands', $c );
			return;
		} elseif ( !isset( $events['cmds'][$args[0]]['help'] ) ) {
			$dAmn->say( 'There is no help for this command, beat the author.', $c );
			return;
		} else {
			$dAmn->say( "$f " . $events['cmds'][$commandname]['help'], $c );
		}
		return;
	}
	//Parse command
	//Use REGEX Commands
	foreach( $events['cmds'] as $command => $cmdinfo ) {
		if( substr( $command, 0, 1 ) == "/" ) {
			if( preg_match( $command, $commandname, $matches ) ) {
				if( !$user->has( $from, $config['access']['priv'][$command] ) ) {
					debug( "Incoming command \"" . $command . "\" was ignored as user " . $from . " does not have the access level." );
					command_fail( 'priv', $c, $command, $from );
				} elseif( !$events['cmds'][$command]['status'] ) {
					debug( "Incoming command \"" . $command . "\" is switched off and was ignored." );
					if ( $user->has( $from, $config['access']['default']['errors'] ) ) {
						$dAmn->say("Command is turned off",$c);
					}
				} else {
					$event = "cmd";
					$evtby = $from;
					if ( file_exists( f( "modules/" . $events['cmds'][$command]['file'] ) ) ) {
						include f( "modules/" . $events['cmds'][$command]['file'] );
					}
				}
				return;
			}
		}
	}

	//Search for a module command
	if( isset( $events['cmds'][$commandname] ) ) {
		if( !$user->has( $from, $config['access']['priv'][$commandname] ) ) {
			debug( "Incoming command \"" . $commandname . "\" was ignored as user " . $from . " does not have the access level." );
			command_fail( 'priv', $c, $commandname, $from );
		} elseif( !$events['cmds'][$commandname]['status'] ) {
			debug( "Incoming command \"" . $commandname . "\" is switched off and was ignored." );
			if ( $user->has( $from, $config['access']['default']['errors'] ) ) {
				$dAmn->say("Command is turned off",$c);
			}
		} else {
			$event = "cmd";
			$evtby = $from;
			if ( file_exists( f( "modules/" . $events['cmds'][$commandname]['file'] ) ) ) {
				include f( "modules/" . $events['cmds'][$commandname]['file'] );
			}
		}
		return;
	}
	// Search for a script command.
	if( !empty( $scripts[$commandname]['filename'] ) ) {
		if( file_exists( f( "scripts/" . $scripts[$commandname]['filename'] ) ) ) {
			if( $user->has( $from, $config['access']['priv'][$commandname] ) ) {
				include f( "scripts/" . $scripts[$commandname]['filename'] );
			} else {
				debug( "Incoming command \"" . $commandname . "\" was ignored as user " . $from . " does not have the access level." );
				command_fail( 'priv', $c, $commandname, $from );
			}
		}
		return;
	}

	$fail = TRUE;
	global $filecache;
	$event = "cmd-fail";
	include f( "system/callables/event.php" );

	if ( $user->has( $from, $config['access']['default']['errors'] ) ) {
		if ( $fail ) {
			command_fail( 'badfoo', $c, $commandname, $from );
		}
	}
	return;
}

/**
 * Processes DataShare information
 *
 *@author NarratorOfTownsville
 *@version 0.1
 */
 function DataShare($from, $message = null, $c = "#DataShare"){
	global $config, $modules, $dAmn;
	if($from == "j"){
		//$dAmn->say(".",$c);
		$dAmn->npmsg("BDS:PROVIDER:CAPS:DANTE-NOTE,DANTE-UPDATE,BOTCHECK,BOTCHECK-EXT", $c, TRUE);
		return;
	}
	if(isset($config['bot']['owners'])) {
		$Master = strtolower($config['bot']['owners'][0]);
	} else {
		$Master = strtolower($config['bot']['owner']);
	}
	if(substr($message, 0, 4) == "DDS:") {
		$command = explode(":", $message, 4);
		switch($command[1]) {
			case "NOTE":
				switch($command[2]) {
					case "NEW":
						if(substr($c, 0, 1) == "@"){
							$command[3] = explode(",", $command[3], 7);
							$command[3][1] = strtolower($command[3][1]);
							if( !$config['notes'][$command[3][1]] ) $config['notes'][$command[3][1]] = array();
							$config['notes'][$command[3][1]][$command[3][4]]['note'] = '<b>From</b> ' . $command[3][0] . ' <b>on</b> ' . date( "D, d M Y h:i:s O", $command[3][2] ) . '<br><i>' . $command[3][6] . '</i>';
							$config['notes'][$command[3][1]][$command[3][4]]['read'] = $command[3][5];
							$config['notes'][$command[3][1]][$command[3][4]]['read2'] = $command[3][5];
							$dAmn->npmsg("DDS:NOTE:CONFIRM:Recieved", $c);
							$dAmn->partRoom($c);
							save_config( 'notes' );
						}
						break;
					case "DELETE":
						$command[3] = explode(",", $command[3]);
						foreach($command[3] as $useless => $notuseless) {
							if(isset($config['notes'][strtolower($notuseless)]) && isset($config['notes'][strtolower($notuseless)][$command[3][$useless + 1]])) {
								unset($config['notes'][strtolower($notuseless)][$command[3][$useless + 1]]);
							}
						}
						save_config('notes');
						break;
					case "UNREAD":
						$command[3] = explode(",", $command[3]);
						foreach($command[3] as $useless => $notuseless) {
							if(isset($config['notes'][strtolower($notuseless)]) && isset($config['notes'][strtolower($notuseless)][$command[3][$useless + 1]])) {
								$config['notes'][strtolower($notuseless)][$command[3][$useless + 1]]['read'] = "False";
								$config['notes'][strtolower($notuseless)][$command[3][$useless + 1]]['read2'] = "False";
							}
						}
						save_config('notes');
						break;
					case "READ":
						$command[3] = explode(",", $command[3]);
						foreach($command[3] as $useless => $notuseless) {
							if(isset($config['notes'][strtolower($notuseless)]) && isset($config['notes'][strtolower($notuseless)][$command[3][$useless + 1]])) {
								$config['notes'][strtolower($notuseless)][$command[3][$useless + 1]]['read'] = "True";
								$config['notes'][strtolower($notuseless)][$command[3][$useless + 1]]['read2'] = "True";
							}
						}
						save_config('notes');
						break;
					case "CONFIRM":
						$dAmn->partRoom($c);
						break;
				}
				break;
			case "UPDATE":
				switch($command[2]) {
					case "INFO":
						$update = explode(", ", $command[3]);
						if($update[0] == strtolower($config['bot']['username'])){
							if($from == "BotsGalore-Bot"){
								file_put_contents("./version", $update[3]);
								switch($update[1]){
									case "00":
										$file = file_get_contents(base64_decode($update[2]));
										file_put_contents("./system/classes/damn.php", $file);
										break;
									case "01":
										$file = file_get_contents(base64_decode($update[2]));
										file_put_contents("./main.php", $file);
										break;
									case "02":
										$file = file_get_contents(base64_decode($update[2]));
										file_put_contents("./system/functions/general_functions.php", $file);
										break;
									case "03":
										$file = file_get_contents(base64_decode($update[2]));
										file_put_contents("./system/functions/net_functions.php", $file);
										break;
									case "04":
										$file = file_get_contents(base64_decode($update[2]));
										file_put_contents("./system/functions/config_functions.php", $file);
										break;
									case "05":
										$file = file_get_contents(base64_decode($update[2]));
										file_put_contents("./system/classes/parser.php", $file);
										break;
									case "06":
										$file = file_get_contents(base64_decode($update[2]));
										file_put_contents("./init.php", $file);
										break;
									default:
										$file = file_get_contents(base64_decode($update[2]));
										file_put_contents(base64_decode($update[1]), $file);
										break;
								}
								if($update[4] == "TRUE"){
									file_put_contents(f('restart.txt'),"Don't delete this file while the bot is running.\n".$dAmn->disconnectCount."\nBot\n".time()."\nW00t");
									quit("BotsGalore due to updates.", FALSE);
								}
							}
						}
						break;
					case "RESTART":
						if(strtolower($command[3]) == strtolower($config['bot']['username'])) {
							file_put_contents(f('restart.txt'),"Don't delete this file while the bot is running.\n".$dAmn->disconnectCount."\nBot\n".time()."\nW00t");
							quit("BotsGalore due to updates.", FALSE);
						}
						break;
				}
				break;
		}
	}
	if($c == "#DataShare" && (substr($message, 0, 4) == "BDS:")) {
		$command = explode(":", $message, 4);
		switch($command[1]) {
			case "BOTCHECK":
				switch($command[2]) {
					case "RESPONSE":
						$from = strtolower($from);
						$data = explode(",", $command[3], 6);
						$data[3] = explode("/", $data[3]);
						if(strpos($data[1], ";")){
							$subdata = explode(";", $data[1]);
							$data[1] = $subdata[0];
						}
						$config['database'][$from]['updateby'] = $data[0];
						$config['database'][$from]['updateat'] = time();
						$config['database'][$from]['owner'] = $data[1];
						$config['database'][$from]['type'] = $data[2];
						$config['database'][$from]['botversion'] = $data[3][0];
						$config['database'][$from]['dsversion'] = $data[3][1];
						$config['database'][$from]['hash'] = $data[4];
						$config['database'][$from]['trigger'] = $data[5];
						save_config('database');
						break;
					case "INFO":
						$classes = $dAmn->room['#datashare']->getHierarchy();
						if(in_array($from, $classes['PoliceBot'])){
							$data = explode(",", $command[3], 5);
							$data[2] = explode("/", $data[2]);
							$config['database'][strtolower($data[0])]['type'] = $data[1];
							$config['database'][strtolower($data[0])]['botversion'] = $data[2][0];
							$config['database'][strtolower($data[0])]['dsversion'] = $data[2][1];
							$config['database'][strtolower($data[0])]['owner'] = $data[3];
							$config['database'][strtolower($data[0])]['trigger'] = $data[4];
							save_config('database');
						}
						break;
					case "BADBOT":
						$classes = $dAmn->room['#datashare']->getHierarchy();
						if(in_array($from, $classes['PoliceBot'])){
							$data = explode(",", $command[3], 8);
							$config['database'][strtolower($data[0])]['type'] = $data[2];
							$config['database'][strtolower($data[0])]['botversion'] = $data[3];
							$config['database'][strtolower($data[0])]['status'] = $data[4];
							$config['database'][strtolower($data[0])]['owner'] = $data[1];
							$config['database'][strtolower($data[0])]['trigger'] = $data[7];
							$config['database'][strtolower($data[0])]['bannedby'] = $data[5];
							$config['database'][strtolower($data[0])]['updateat'] = $data[6];
							save_config('database');
						}
						break;
					case "ALL":
						$dAmn->npmsg("BDS:BOTCHECK:RESPONSE:".$from.",".$Master.",".BotType.",".BotVersion."/".$modules['DataShare']->version.",".md5(strtolower(trim($config['bot']['trigger']).trim($from).trim($config['bot']['username']))).",".$config['bot']['trigger'], rChatName("#DataShare"), TRUE);
						break;
					case "DIRECT":
					case "NODATA":
						if(strtolower($command[3]) == strtolower($config['bot']['username'])) {
							$dAmn->npmsg("BDS:BOTCHECK:RESPONSE:".$from.",".$Master.",".BotType.",".BotVersion."/".$modules['DataShare']->version.",".md5(strtolower(trim($config['bot']['trigger']).trim($from).trim($config['bot']['username']))).",".$config['bot']['trigger'], rChatName("#DataShare"), TRUE);
						}
						break;
					case "REQUEST":
						$classes = $dAmn->room['#datashare']->getHierarchy();
						if(in_array($config['bot']['username'], $classes['PoliceBot'])){
							if(isset($config['database'][strtolower($command[3])])){
								$bot = $config['database'][strtolower($command[3])];
								if(!isset($bot['bannedby']))
									$dAmn->npmsg("BDS:BOTCHECK:INFO:".$command[3].",".$bot['type'].",".$bot['botversion']."/".$bot['dsversion'].",".$bot['owner'].",".$bot['trigger'], rChatName("#DataShare"), TRUE);
								else
									$dAmn->npmsg("BDS:BOTCHECK:BADBOT:".$command[3].",".$bot['owner'].",".$bot['type'].",".$bot['botversion'].",".$bot['status'].",".$bot['bannedby'].",".$bot['updateat'].",".$bot['trigger'], rChatName("#DataShare"), TRUE);
							} else
								$dAmn->npmsg("BDS:BOTCHECK:NODATA:".$command[3], rChatName("#DataShare"), TRUE);
						}
				}
				break;
		}
	}
}
?>
