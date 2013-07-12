<?php
/**
 *Dante
 *
 *By: LabX52 Bot Development Team.
 *
 *"If it's open source it's not 'stealing',  it's 'sharing'"
 *
 * This loads the system files. It loads all files in %HOMEDIR%/system up to one folder deep and excludes files in %HOMEDIR%/system/callables.
 *
 * This make adding classes and new functions very easy but a bad file can crash the bot easier. It's a tradeoff.
 *@author SubjectX52873M <SubjectX52873M@gmail.com>
 *@copyright SubjectX52873M 2006
 *@package Dante
 *@license http://www.opensource.org/licenses/gpl-license.php GNU General Public License
 *@origin Dante 0.1
 *@version 0.6
 */
//Let's share away then! :D

declare( ticks = 1 );
if( function_exists( 'pcntl_signal' ) ) {
  function quit_sigterm( $s ) {
		global $dAmn;
		echo "Caught SIGTERM!\n";
		foreach( $dAmn->room as $room => $data ) {
			$dAmn->say( "Shutting down as requested from command line. (Received SIGTERM)", $room );
		}
		quit('*');
	}
	
	if( pcntl_signal( SIGTERM, "quit_sigterm" ) ) {
		echo "Signal Handler installed.\n";
	} else {
		echo "Error: Could not install signal handler\n";
	}
	
	file_put_contents( f( 'Dante.pid' ), posix_getpid() );
}

//Spoof the bot as a webbrowser to websites.
define( "BotHttpUserAgent", "Mozilla/5.0 (Windows; U; Windows NT 6.0; en-US; rv:1.8.1.11) Gecko/20071127 Firefox/2.0.0.11" );
ini_set( 'user_agent',  BotHttpUserAgent );
//Stores Version and server infomation.
/**
 *Type of bot
 *@constant string BotType
 */
define( "BotType", "Dante" );
/**
 *Version of the Bot
 *@constant string BotVersion
 */
define( "BotVersion", "0.13" );
/**
 *Development state
 *@constant string BotState
 */
define( "BotState", "§&#x0474;&#x019D;");
/**
 *SVN Revision
 * @constant string BotRevision
 */
if( file_exists( $temp = f( 'CHANGELOG-TRUNK.txt' ) ) ) {
	$changelogcontent = file_get_contents( $temp );
	if( preg_match( '/\$Rev: ([0-9]*) \$/', $changelogcontent, $matches ) ) {
		define( "BotRevision", $matches[1] );
	} else define( "BotRevision", '' );
} else define( "BotRevision", '' );
/**
 *Client version string
 *@constant string dAmnclient
 */
define( "dAmnclient", "dAmnClient 0.2" );
/**
 *Useragent for login use
 *
 *Not yet implemented.
 *@constant string BotAgent
 */
define( "BotAgent", BotType.'/'.BotVersion.' '.BotState );
/**
 *Server the bot connects to.
 *@constant string BotServer
 */
define( "BotServer", "chat.deviantart.com" );
/**
 *Port of the Server that the bot connents to.
 *@constant string BotPort
 */
define( "BotPort", "3900" );
/**
 *UserAgent for the bot.
 *
 *This is not the useragent that is use for opening websites.
 *
 *@see dAmn::getAuthToken()
 *@constant string BotUserAgent
 */
define( "BotUserAgent", BotAgent );
/**
 *Regular Expression to match mail
 *@constant string EmailRegEx
 */
define( "EmailRegEx", '/( [a-zA-Z0-9\-_\.]+ )@( [a-zA-Z0-9\-\.]+ ).( [a-zA-Z0-9]{2, 4} )/i' );
/**
 *SessionID
 *@constant string SessionID
 */
define( "SessionID", sha1( uniqid( "", TRUE ) ) );

//Error Handler
define( 'ZAPI_SHOW_NOTICES', FALSE );
define( 'CONSOLE_ANSICOLOR', FALSE );
define( 'ZAPI_SHOW_ERRORS', TRUE );
//define( 'ZAPI_SHOW_NOTICES', TRUE );
define( 'ZAPI_SHOW_WARNINGS', TRUE );
define( 'ZAPI_SOCKET_WARNING', FALSE );
define( 'ZAPI_APPLICATION_STACKTRACE', TRUE );
date_default_timezone_set( date_default_timezone_get() );

//If debugging switch to reporting all errors
//Leaving as a variable so you can switch in and out of debug mode.
if ( array_search( "debug",  $_SERVER['argv'] ) !==  FALSE ) {
	global $debugLevel;
	$debugLevel = 5;
	define( "CanRestart", FALSE );
} elseif ( array_search( "batch",  $_SERVER['argv'] ) !==  FALSE ) {
	/**
	 *Constant that tells the bot if it was launched from the batch file.
	 *@constant bool CanRestart
	 */
	define( "CanRestart", TRUE );
} else {
	define( "CanRestart", FALSE );
}

//Discover the bot's OS
$X = "";
$Xos = php_uname( "s" );
$Xversion = php_uname( "r" );
if ( substr( $Xos, 0, 3 ) == "Win" ) {
	$X = "Windows ";
	switch ( $Xversion ) {
		case "6.1":
			$X .= "7";
			break;
		case "6":
			$X .= "Vista";
			break;
		case "5.1":
			$X .= "XP";
			break;
		case "5.2":
			$X .= "Server 2003";
			break;
		case "5":
			$X .= "2000";
			break;
		case "4":
			$X .= "NT";
			break;
		default:
			$X .= "(Unknown Version)";
			break;
	}
	// Load the OpenSSL module for PHP...or try to.
	if( !extension_loaded( "openssl" ) ) {
		if( !@dl( "php_openssl.dll" ) ) {
			echo "Error: Could not load OpenSSL module.  You may not be able to retreive your authtoken.\n";
		}
	}
} else $X = $Xos;
/**
 *Stores the Operating System of the bot
 *@constant string BotOS
 */
define( "BotOS", $X );
/**
 *Stores the bot's configuration
 *@global array $config
 */
$config = array();

/**
 *List of Official Chatrooms
 *
 *@global array $officialchats
 */
$officialchats = array( "chat:devart",  "chat:deviantart",  "chat:devious",  "chat:fella",  "chat:help",  "chat:mnadmin",  "chat:irpg" );
// iRPG may not be official but I'm not allowing data to be sent.

$running = FALSE;

$seperator = "----------------------------------------";

//Whois variables... duh :D
$whoisroom = array();
$latestwhois = array();

/**
 * Stores chatroom information
 *@global array $room
 */
$room = array();

global	$config,  $running,  $seperator,  $modules,  $event,  $whoisroom, $latestwhois,  $room,  $builtins;

/*Functions for loading*/

/**
 *Checks for a .php extension on files
 *@return bool
 *@version 0.2
 */
function is_php( $file ) {
	if ( pathinfo( $file, PATHINFO_EXTENSION ) == "php" ) {
		return TRUE;
	} else {
		return FALSE;
	}
}
/**
 *Checks for a {$extension} extension on files
 *
 * To test for a php file use: is_type( $file, 'php' );
 *
 * To test for a html file use: is_type( $file, 'html' );
 *@return bool
 *@version 0.2
 */
function is_type( $file, $extension ) {
	if ( pathinfo( $file, PATHINFO_EXTENSION ) == $extension ) {
		return TRUE;
	} else {
		return FALSE;
	}
}

/*
 * Load Internal Components
 * This code includes system php files for the bot.<br/>
 * This needs to be reduced to scandir()<br/>
 *
 * Notice,  it only goes one folder deep,  it also ignores the "callables" folder.
 */
$listF = array();
if ( $listD = opendir( f( "system" ) ) ) {
	while( FALSE !==  ( $file = readdir( $listD ) ) ) {
		if ( $file !=  ".." && $file !=  "." ) {
			$listF[] = $file;
		}
	}
	if ( count( $listF ) > 0 ) {
		foreach( $listF as $m ) {
			$m = "system/".$m;
			if ( is_dir( f( "$m" ) ) && $m !=  strtolower( "system/callables" ) ) {
				//debug( "DIR FOUND $m", 3 );
				$listFT = array();
				if ( $listDT = opendir( f( "$m" ) ) ) {
					//debug( "DIR SCAN $m", 3 );
					while( FALSE !==  ( $file = readdir( $listDT ) ) ) {
						if ( $file !=  ".." && $file !=  "." ) {
							//debug( "DIR MATCH $m/$file", 3 );
							$listFT[] = $file;
						}
					}

					if ( count( $listFT ) > 0 ) {
						foreach( $listFT as $n ) {
							$n = $m . "/" . $n;
							if ( is_file( f( "$n" ) ) and is_php( "$n" ) ) {
								//debug( "FILE: Loading $n", 3 );
								/**
								 * Includes files in subfolders of system. Except the callables folder
								 */
								include( "$n" );
							}
						}
					}
					closedir( $listDT );
				}

			}
			if ( is_file( F( "$m" ) ) && is_php( "$m" ) ) { //X52 for a file,  check for .php extension
				//debug( "FILE $m ", 3 );
				/**
				 * Includes files in folder of system
				 */
				include( "$m" );
			}
		}
	} else {
		die( "Empty system folder. This is usually a problem." );
	}
} else {
	die( 'No system folder. This is usually a problem.' );
}
closedir( $listD );
?>
