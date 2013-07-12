<?php
/**
 *Random Functions
 *@license http://www.opensource.org/licenses/gpl-license.php GNU General Public License
 *@author SubjectX52873M <SubjectX52873M@gmail.com>
 *@version 0.8
 */

/**
 *Pchat sorting
 *@return string
 *@version 0.4
 */
function sortpchat( $input, $user ) {
  if ( empty( $input ) ) return;
	if ( substr( $input, 0, 5 ) == "chat:" || substr( $input, 0, 1 ) == "#") return $input;
	elseif( substr( $input, 0, 6 ) == "pchat:" ) {
		$input = str_replace( "pchat:", "", $input );
		$input = explode( ":", $input );
	} elseif( substr( $input, 0, 1 ) == "@" ) {
		$input = str_replace( "@", "", $input );
		$input = explode( ":", $input );
	} elseif ( strstr( $input, ":" ) ) {
		$input = explode( ":", $input );
	} else {
		return $input;
	}
	global $config;
	if ( $user === FALSE) $user = $config['bot']['username'];
	if ( count( $input ) == 1 ) $input[] = $user;
	sort( $input );
	return $input[0] . ':' . $input[1];
}

/**
 *Format chat room properly
 *
 *$forSocket=true : Turn #room or room into chat:room
 *
 *$forSocket=false : Turn chat:room into #room
 *
 *$user is the user of pchat to be parsed out (Normally uses the bot's name)
 *@return mixed false on error, string otherwise
 *@author SubjectX52873M
 *@version 0.9
 */
function generateChatName( $input, $forSocket = TRUE, $user = FALSE ) {
	global $config;
	if( $user == FALSE) $user = $config['bot']['username'];
	if( substr( $input, 0, 5 ) != "chat:" && substr( $input, 0, 6 ) != "pchat:" && substr( $input, 0, 1 ) != "#" && substr( $input, 0, 1 ) != "@" ) {
		if ( strstr( $input, ":" ) ) {
			$tmp = explode( ":", $input, 2 );
			natcasesort( $tmp );
			$input = $tmp[0] . ':' . $tmp[1];
			$return = 'pchat:' . $input;
		} else $return = "chat:" . $input;
	} else {
		if( substr( $input, 0, 1 ) == "#" ) {
			//Normal room
			$return = str_replace( "#", "chat:", $input );
		} elseif ( substr( $input, 0, 1 ) == "@" ) {
			$input = substr( $input, 1 );
			//pchat room
			$tmp = explode( ":", $input, 2 );
			if( count( $tmp ) ) $tmp[] = $user;
			usort( $tmp, "icmp" );
			$input = $tmp[0] . ':' . $tmp[1];
			$return = 'pchat:' . $input;
		} elseif( substr( $input, 0, 5 ) == "chat:" ) {
			$return = $input;
		} elseif( substr( $input, 0, 6 ) == "pchat:" ) {
			$input = substr( $input, 6 );
			//pchat room
			$tmp = explode( ":", $input );
			if( count( $tmp ) == 1 ) $tmp[] = $user;
			usort( $tmp, "icmp" );
			$input = $tmp[0] . ':' . $tmp[1];
			$return = 'pchat:' . $input;
		} else {
			$return = FALSE;
		}
	}
	if($forSocket) return $return; 
	else {
		$temp = explode( ":", $return, 2 );
		$chat = $temp[1];
		if ( strstr( $chat, ":" ) ) {
			$chat = str_ireplace( $user, "", $chat );
			$chat = str_replace( ":", "", $chat );
			return "@" . $chat;
		} elseif ( $chat != FALSE ) {
			return "#" . $chat;
		}
	}

} function gChatName( $input, $forSocket = TRUE, $user = FALSE ) { return generateChatName( $input, $forSocket, $user ); }

function icmp( $a, $b ) {
	if ( $a == $b ) return 0;
	return ( strtolower( $a ) < strtolower( $b ) ) ? -1 : 1;
}

/**
 * Turn room or chat:room into #room
 *
 *$user is the user of pchat to be parsed out (Normally uses the bot's name)
 *@return string
 *@author SubjectX52873M <SubjectX52873M@gmail.com>
 *@version 0.9
 */
function regenerateChatName( $input, $user = FALSE ) {
	return generateChatName( $input, FALSE, $user );
}function rChatName( $input ) { return regenerateChatName( $input, $user = FALSE ); }

/**
 *Fixes access privs
 *@author SubjectX52873M <SubjectX52873M@gmail.com>
 *@return mixed
 *@version 0.7
 */
function accessFix( $int, $mode = FALSE ) {
	$check = $int;
	if ( !is_numeric( $check ) ) {
		if ( $mode ) $int = 0;
		else $int = "default";
	} else {
		$check = floor( $check );
		if ( $check < -1 ) $int = -1;
		elseif ( $check > 100 )  $int = 100;
		else $int = $check;
	}
	return $int;
}
/**
 *Fixes access privs, array walk version
 *@author SubjectX52873M <SubjectX52873M@gmail.com>
 *@return mixed
 *@version 0.7
 */
function accessFixWalk( &$int, $key, $mode = FALSE ) {
	$check = $int;
	if ( !is_numeric( $check ) ) {
		if ($mode) $int = 0;
		else $int = "default";
	} else {
		$check = floor( $check );
		if ( $check < -1 ) $int = -1;
		elseif ( $check > 100 ) $int = 100;
		else $int = $check;
	}
}

/**
 *Make a timestamp
 *
 *Uses $config['bot']['timestamp'] for the format
 *@author electricnet
 *@author SubjectX52873M <SubjectX52873M@gmail.com>
 *@version 0.9
 */
function ts( $open = FALSE ) {
	global $config;
	return ( $open ) ? ( date( $config['bot']['timestamp'] ) ) : ( date( $config['bot']['timestamp'] ) . " " );
}
/**
 * Timestamped message to the console
 * @return void
 */
function internalMessage( $message ) {
	echo ts() . $message . "\n";
} function intMsg( $message ) { internalMessage( $message ); }
/**
 * Timestamped header to the console
 * @return void
 */
function internalHeader( $message, $sepchar = "-" ) {
	$message = trim( strtoupper( $message ) );
	$seperator = str_repeat( $sepchar{0}, 64 - strlen( $message ) );
	intMsg( "** " . $message . " " . $seperator );
} function intHeader( $m, $s = "-" ) { internalHeader( $m, $s ); }
/**
 * Add a seperator to the console
 * @return void
 */
function internalSeperator() {
	internalMessage( str_repeat( "-", 65 ) );
}

/**
 *Debug messages
 *
 *if $message=false you can use this function to check against the debugging level
 *return bool true if debugLevel is within the range given, false if not
 */
function debug( $message = FALSE, $lower = 2, $upper = 5 ) {
	global $debugLevel;
	if( $debugLevel <= $upper && $debugLevel >= $lower ) {
		if( $message !== FALSE ) internalMessage( $message );
		return true;
	}
	return false;
}
function arg($args, $start=0, $length=1, $separator=' ')
{
	$arg = "";
	$args = explode($separator, $args);
	if($start<0) $start=count($args)+$start;
	if($start>count($args)) return "";
	if($length===true) $length=count($args)-$start;
	if($length==0) return "";
	if($length<0) $length=count($args)+$length;
	
	for($i=$start; $i<$start+$length && $i<count($args); $i++)
	{
		$arg.=$args[$i];
		$arg.=($i<($start+$length-1)) ? $separator : "";
	}

	return $arg;	
}

/**
 * Used in dAmn::process() to log and printout to the terminal
 *@return void
 *@version 0.4
 */
function registerChat( $message, $chatroom, $toConsole = TRUE ){
	global $config;
	if( $toConsole == TRUE) intMsg( $message );

	//LOGGING
	if ( $config['bot']['logChats'] == TRUE && $config['bot']['thumbMode'] == FALSE ) {
		//Prepare variables
		$chatname 		=	 rChatName( $chatroom );
		$mts 			= 	date( "Y-m" );
		$dirname 		= 	"logs/" . $chatname;
		$logMessage 	= 	str_replace( "[" . $chatname . "] ", "", $message );
		$fn 			= 	"$chatname " . date( "Y-m-d" );
		//Make the directory
		if( !is_dir( f( "logs" ) ) ) 			mkdir( f( "logs" ) );
		if( !is_dir( f( $dirname ) ) ) 			mkdir( f( $dirname) );
		if( !is_dir( f( "$dirname/$mts" ) ) ) 	mkdir( f( "$dirname/$mts" ) );
		$logfile = fopen( f( "$dirname/$mts/$fn.txt" ), "a" );
		fwrite( $logfile, "[" . date( $config['bot']['timestamp_log'] ) . "] " . $logMessage . "\r\n" );
		fclose( $logfile );
	}
}

/**
 *Enhanced logging function
 *@return void
 *@version 0.9
 */
function eLog( $message, $chatroom, $toConsole = TRUE ) {
	if( EnhancedLogging ) registerChat( $message, $chatroom, $toConsole );
}

//Format a number of seconds into a nice array
/**
 *Format Time
 *
 * Changes a time in seconds to an array
 * Used in dAmn::process() to log and printout to the terminal
 *@return array array('sec'=>$sec,'min'=>$min,'hour'=>$hour,'day'=>$day)
 *@version 0.6
 */
function formatTime( $int ) {
	$int	=	floor( $int );
	$sec	=	0;
	$min	=	0;
	$hour	=	0;
	$day	=	0;

	while ( $int > 0 ) {
		if ( $int < 60 ) {
			$sec = $int;
			$int = 0;
		} else {
			$int = $int - 60;
			$min++;
			if ( $min >= 60 ) {
				$min = $min - 60;
				$hour++;
				if ( $hour >= 24 ) {
					$hour = $hour-24;
					$day++;
				}
			}
		}
	}
	$array  = array(
		'sec'	=>	$sec,
		'min'	=>	$min,
		'hour'	=>	$hour,
		'day'	=>	$day,
	);
	
	return $array;
}
/**
 *Return a time string
 *return string
 */
function timeString( $int, $short = FALSE ) {
	$time = formatTime( $int );
	if($short) $say = " {$time['day']} days, {$time['hour']} hrs, {$time['min']} mins, {$time['sec']} secs";
	else $say = " {$time['day']} days, {$time['hour']} hours, {$time['min']} minutes, {$time['sec']} seconds";

	$say = preg_replace( "/ 0 ([a-z]+),/", 	" ", 		$say ); // Stripping out zero's
	$say = preg_replace( "/ 1 ([a-z]+)s,/", " 1 \\1,", 	$say ); // Fixing one's
	$say = preg_replace( "/ 0 ([a-z]+)/", 	" ", 		$say );
	$say = preg_replace( "/ 1 ([a-z]+)s/", 	" 1 \\1", 	$say );
	$say = trim( $say, ", " );
	if( empty( $say ) ) {
		if( $short ) $say = "0 secs";
		else $say = "0 seconds";
	}
	return $say;
}

/**
 * Comma - SemiColon spilt
 *
 * Spilts sutff, stuff, stuff; more stuff
 *
 * Into ("array"=>array("sutff, "stuff", "stuff"), "msg"=>"more stuff"
 *
 *@return array
 */
function cscarg( $stuff ) {
	//Try this way
	$temp_array = explode( ";", $stuff, 2 );
	$temp_data = explode( ",", $temp_array[0] );
	$data = array(
		"array" 	=>	$temp_data,
		"msg"		=>	$temp_array[1],
	);
	return $data;
}

/**
 *Used for parsing privlevels in process_command()
 *@return mixed
 *@version 0.6
 */
function parsePriv( $lvl ) {
	global $config;
	if ( $lvl == 'default' ) {
		$lvl = $config['access']['default']['commands'] . '*';
	}
	return $lvl;
}

/**
 *Random ':bullet'.$color.':'
 *@author electricnet
 *@return string
 *@version 0.2
 */
function randobullet() {
	$colours = array("red", "green", "blue", "purple");
	return ":bullet" . $colours[array_rand( $colours )] . ":";
}
?>
