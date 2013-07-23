<?php
/**
 * Network and Command related functions.
 *@author SubjectX52873M <SubjectX52873M@gmail.com>
 *@copyright SubjectX52873M 2006
 *@package Dante
 *@license http://www.opensource.org/licenses/gpl-license.php GNU General Public License
 *@version 0.8
 */

 
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
			if( !empty( $config->bot['RestrictedCommandMsg'] ) ) {
				if ( $user->has( $from, $config->df['access']['default']['errors'] ) ) {
					$txt = $config->df['access']['RestrictedCommandMsg'];
					$txt = str_replace("{command}", $command, $txt );
					$txt = str_replace("{from}", $from, $txt );
					$dAmn->say( $txt, $c );
				}
			} else {
				if ( $user->has( $from,$config->df['access']['default']['errors'] ) )
				$dAmn->say( 'You do not have the privs to access "' . $command . '".', $c );
			}
			break;
		case "badfoo":
			if( !empty($config->bot['BadCommandMsg'] ) ) {
				if ( $user->has( $from, $config->df['access']['default']['errors'] ) ) {
					$txt = $config->bot['BadCommandMsg'];
					$txt = str_replace( "{command}", $command, $txt );
					$txt = str_replace( "{from}", $from, $txt );
					$dAmn->say( $txt, $c );
				}
			} /*else {
			if ($user->has($from,$config->df['access']['default']['errors']))
			$dAmn->say("\"$command\" is not a valid command.",$c);
			}*/
			intmsg( "Unknown command: \"$command\"." );
			break;
		default:
			if ( $user->has( $from,$config->df['access']['default']['errors'] ) )
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
function quit( $from, $fromchatroom = "", $s = FALSE, $notcon = FALSE ) {
	global $dAmn, $config, $socket;
	if( $fromchatroom ) {
		$f = $from . ": ";
		if( $s ) $dAmn->say( "$f Shutting down.", $fromchatroom );
	}
	//Shutdown gracefully
	if( !$notcon ) {
		$dAmn->send( "disconnect\n" . chr( 0 ) );
	}
	//Let's save everything. 
	foreach( $config->df as $file => $contents ){
		$config->save_info( "./config/$file.df", $config->df[$file] );
	}
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
		if ( $chatroom == "#dAmnIdlers" || stristr(strtolower($message), "damnidlers") ){
			return;
		}
		$bZikes = $config->df['bzikes2']['status'];
		if( $bZikes == TRUE ){
			$biz = strtolower( substr( $message, 0, strlen( $config->bot['trigger']."bzikes" ) + 1 ) );
			if(substr( $message, 0, strlen( $config->bot['trigger']."bzikes" ) ) != $config->bot['trigger']."bzikes"){
				foreach( $config->df['bzikes'] as $code => $emotes ){
					$message = str_ireplace($code, ":thumb".$emotes.":", $message);
				}
			}
		}
		$c = $chatroom;
		$f = $from . ": ";
		$namePart = strtolower( substr( $message, 0, strlen( $config->bot['username'] ) + 1 ) );
		if($namePart == strtolower( $config->bot['username'] ) . ":" || $namePart == strtolower( $config->bot['username'] ) . "," ) {
			if( trim( substr( $message, ( strlen($config->bot['username']) + 1 ) ) ) == "trigcheck" ) {
				if ( $user->has( $from, $config->df['access']['default']['trigcheck'] ) ) {
					$dAmn->say( $f . $config->df['bot']['trigger'], $c );
				}
			}
			$message = str_replace($config->bot['username']  . ": ",$config->bot['trigger'],$message);	
		}
		//If it doesn't say what type it is, it's probably an ordinary message.
		if( empty( $msgtype ) ) $msgtype = "msg";
		//Ping stuff. Builtin so that if modules fail you still have a ping.
		if( strtolower( $from ) == strtolower( $config->bot['username'] ) ) {
			if ( $message === "Ping?" && $pingt != 0 ) {
				$ping = microtime( TRUE ) - $pingt;
				$ptmp = round( ( $ping * 1000 ), 2 );
				$dAmn->say( "Pong! ($ptmp ms)", $c );
				$pingt = 0;
			}	//<l337a> Oo! Tell them i said hi!
			//return false;
		} //Strip out the bot to prevent loops.
			
		if( $message == "bots: roll call" && $user->has( $from,99 ) ) {
			$dAmn->say( "$from: <b>" . $config->bot['username'] . "</b> is online!", $c );
		}


		//Kick it to command parser if it's a command
		if( substr( $message, 0, strlen( $config->bot['trigger'] ) ) == $config->bot['trigger'] ) {

			//Flood protection
			$x = FALSE;
			if( !$user->has( $from,$config->df['access']['flood']['privs'] ) ) $x = $flood->check( $from );
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
	$command = substr( $message, strlen( $config->bot['trigger'] ) );
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
	$tr = $config->bot['trigger'];

	/*
	 * If user has below banned privs they are parsed out.
	 * Banned is more of a restricted class than a "banned" class.
	 * Ignored users are handled in the event system and processmessage()
	 */
	if( is_object( $user ) ){
		if( !$user->has( $from, 0 ) ) return false;
	}
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
				if( !$user->has( $from, $config->df['access']['priv'][$command] ) ) {
					debug( "Incoming command \"" . $command . "\" was ignored as user " . $from . " does not have the access level." );
					command_fail( 'priv', $c, $command, $from );
				} elseif( !$events['cmds'][$command]['status'] ) {
					debug( "Incoming command \"" . $command . "\" is switched off and was ignored." );
					if ( $user->has( $from, $config->df['access']['default']['errors'] ) ) {
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
		if( !$user->has( $from, $config->df['access']['priv'][$commandname] ) ) {
			debug( "Incoming command \"" . $commandname . "\" was ignored as user " . $from . " does not have the access level." );
			command_fail( 'priv', $c, $commandname, $from );
		} elseif( !$events['cmds'][$commandname]['status'] ) {
			debug( "Incoming command \"" . $commandname . "\" is switched off and was ignored." );
			if ( $user->has( $from, $config->df['access']['default']['errors'] ) ) {
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
			if( $user->has( $from, $config->df['access']['priv'][$commandname] ) ) {
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

	if ( $user->has( $from, $config->df['access']['default']['errors'] ) ) {
		if ( $fail ) {
			command_fail( 'badfoo', $c, $commandname, $from );
		}
	}
	return;
}
?>
