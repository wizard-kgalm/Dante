#!/usr/bin/php
<?php
/**
 *Dante Core File
 *
 * Main bot file. This contains the while loop that the bot is in.
 *
 *"If it's open source it's not stealing, it's sharing" - Olyrion
 *@copyright SubjectX52873M (2006)
 *@author SubjectX52873M <SubjectX52873M@gmail.com>
 *@package Dante
 *@license http://www.opensource.org/licenses/gpl-license.php GNU General Public License
 *@orgin Tarbot 0.2e
 *@modlevel medium
 *@version 0.9
 */

//this way you can prevent errorous bug reports
if ( !function_exists( "socket_create" ) ) {
  echo "\nThe socket extension to PHP is not enabled. Refer to the bot's manual for proper PHP setup.\n";
	while ( true ) {
		sleep( 10 );
	}
}

/**
 *Home Directory
 *
 *This grabs the bot's folder by using dirname( __FILE__ );
 *
 *With this the bot can run no matter what the current working directory is.
 *
 *@constant string HOMEDIR
 */
define( "HOMEDIR", str_replace( "\\", "/", dirname( __FILE__ ) ) . "/" );

//Set CWD anyway
chdir( dirname( __FILE__ ) );
/**
 *Internal File Link
 *
 * Makes a relative path into an absolute one.
 *
 *ALL INCLUDE statements should use this: include f( "file" );
 *
 * Also converts \ to / so it works on linux.
 *
 * Calculates from the bot's home folder. (Where main.php is located)
 *@return string
 *@see constant HOMEDIR
 */
function f( $file ) {
	$homedirectory = HOMEDIR; //Use a constant instead of a variable

	$dir = ( empty( $homedirectory ) ) ? realpath( "." ) : $homedirectory;

	$wtf = false;
	if ( ( str_replace( "/", "", $file ) != $file ) && ( str_replace( "/", "", $dir ) == $dir ) && ( str_replace( "\\", "", $dir ) != $dir ) ) {
		$fslash = false;
	} elseif ( ( str_replace( "\\", "", $file ) != $file ) && ( str_replace( "\\", "", $dir ) == $dir ) && ( str_replace( "/", "", $dir ) != $dir ) ) {
		$fslash = true;
	} elseif ( $dir{0} == "/" ) {
		$fslash = true;
	} elseif ( $dir{0} == "\\" ) {
		$fslash = false;
	} elseif ( preg_match( "@[A-Za-z]" . preg_quote( ":\\" ) . "@", substr( $dir, 0, 3 ) ) ) {
		$fslash = false;
	} elseif ( preg_match( "@[A-Za-z]" . preg_quote( ":/" ) . "@", substr( $dir, 0, 3 ) ) ) {
		$fslash = true;
	} else {
		$wtf = true;
	}

	if ( !$wtf ) {
		if ( $fslash ) {
			$dir = ( empty( $homedirectory ) ) ? realpath( "." ) . "/" : $homedirectory;
			$file = str_replace( "\\", "/", $file );
		} else {
			$dir = ( empty( $homedirectory ) ) ? realpath( "." ) . "\\" : $homedirectory;
			$file = str_replace( "/", "\\", $file );
		}
	} else {
		$dir = ( empty( $homedirectory ) ) ? realpath( "." ) . "/" : $homedirectory;
		$file = str_replace( "\\", "/", $file );
	}
	return $dir . $file;
}

if ( file_exists( f( 'config/restart.txt' ) ) ) {
	unlink( f( 'config/restart.txt' ) );
}

/**
 * Loads all system files
 *
 * Loads all php files in /system (Searches one folder deep and ignores the callables folder)
 */
require f( "init.php" );


set_error_handler(  array( 'ZAPI', 'Errorhandler' )  );
//Set Error reporting
if ( debug( false, 3 ) ) error_reporting( E_ALL );
else error_reporting( E_ALL ^ E_NOTICE );

//Make Socket
debug( "Launching Bot\n", 1 );
$connecting = true;
while ( $connecting ) {
	$socket = null;
	$socket = socket_create( AF_INET, SOCK_STREAM, SOL_TCP );
	if ( $socket===false ) {
		echo( "Unable to create socket. Waiting for connection.\n" );
		sleep( 15 );
	} else {
		debug( "socket created", 3 );
		$connecting=false;
	}
}

$config = new config();
if( file_exists( f( "config.ini" ) ) ){
	$check = parse_ini_file( f( "config.ini" ) );
}
if( empty( $check ) || empty( $check['username'] ) ){
	print "\nPlease type your bot's username.\n";
	$username = trim( fgets( STDIN ) );
	print "\nPlease type the name of the owner.\n";
	$owner = trim( fgets( STDIN ) );
	print "\nPlease type the trigger.\n";
	$trigger = trim( fgets( STDIN ) );
	print "\nPlease type the homeroom you want your bot to join.\n";
	$homeroom = trim( fgets( STDIN ) );
	$config->build_config( TRUE, $username, $owner, $trigger, $homeroom );
}
	
//Load config
//load_config();
$config->load_config();
if( $config->update ) {
	$user = new users();
	include f( "system/callables/xload.php" );
	echo "Config has been updated, bot will now restart, just to be safe...";
	file_put_contents( f( 'config/restart.txt' ),"Don't delete this file while the bot is running.\n".$dAmn->disconnectCount."\nBot\n".time()."\nW00t" );
	$config->save_info( "./config/restart2.txt", "For YOOOOOOOOOOOOOOOOOOU" );
	quit( $from, false, FALSE, TRUE );
}
/**
 *Socket for dAmn connection
 *@global $socket resource
 */
global $socket;

//Load dAmn and users classes
$dAmn = new dAmn;

if ( $config->bot['token'] ){
	$dAmn->UseToken = true;
}else{
	$dAmn->UseToken = false;
}

$dAmn->init();

$dAmn->disconnectCount=0;
//Verify that the configuration is set properly.
if( file_exists( "./config/restart2.txt" ) ){
	unlink( "./config/restart2.txt" );
	$config->checkConfig( FALSE );
}else{
	$config->checkConfig( "yes" );
}


//Load flood class!
global $flood, $config;
$s = $config->df['access']['flood'];
$flood  = new flood( $s['use'], $s['period'], $s['times'], $s['timeout'], $s['ruse'], $s['rperiod'], $s['rtimes'], $s['rtimeout'] );



//Load users class, the users list is stored in $config['users']
$user = new users();

//Load timer class.
$Timer = new Timer();
$Timer->init();

/**
 * Load up the scripts in /scripts
 */
include f( "system/callables/load.php" );

/**
 * Load up the modules in /modules
 */
include f( "system/callables/xload.php" );
//This needs to be before $dAmn->init()


//Open a socket and connect.
$connecting = true;
$loops = 0;
while ( $connecting ) {
	if ( @socket_connect( $socket, gethostbyname( BotServer ), BotPort ) ) {
		debug( "Got dAmnserver IP, connecting." );
		$connecting = false;
	} else {
		usleep( 10000 );
		$loops++;
		debug( "No connection, waiting. ({$loops})", 1 );
	}
}
unset( $connecting, $loops );

//Login
$dAmn->startup();

//Autojoin
//This works alot better as a function : )
$dAmn->autoJoin();

//Let the server know we are here
$dAmn->pingpong();
$chatroom = "#rand";
$pro = "input on";
$from = $config->bot['owner'];
parseCommand( $config->bot['trigger'].$pro, $from, $chatroom, "msg" );
//We are go for launch T -5 seconds and counting
$loopTime = $estmicrotime = microtime( true );
$onceconnected = false;
$pingableloop = $iterSinceMTCheck = 0;

define( "StartTime", time() ); //Set the start up time for the bot.
$dAmn->disconnectCount = 0;

while( $running ) {
	usleep( 1000 ); // Delay for the main loop

	if($iterSinceMTCheck++ > 256) { // Check actual microtime every 256 loops.
		$estmicrotime = microtime( true );
		$iterSinceMTCheck = 0;
	} else {
		$estmicrotime += 0.0001;
	}
	
	$dAmn->checkQueues( false );

	//For efficency the rest of the stuff is only called every second.
	if ( $loopTime <= $estmicrotime ) {
		$loopTime = $estmicrotime + 1;

		//Fire delayed events
		$Timer->fire();

		$pingableloop++;
		//Every 15 seconds test the connection.
		if ( $pingableloop >= 14 && debug( false, 0, 3 ) ) {
			$pingableloop = 0;
			//$dAmn->pingpong2();
		}

		// loop event
		// Uncomment the three lines below to use. It is recommended you decrease the main loop delay to 7500 if you are going to use this event.
		// Warning: This will cause extreme memory/CPU usage.
		//$from="!";
		//$event="loop"; // Sweet loop event
		//include f( "system/callables/event.php" );

		$dAmn->timeout(); //Time based disconnect currently sent for 100 seconds

		if ( !$dAmn->connected ) {
			$dAmn->disconnectCount++;
			$event="disconnect";
			/**
			 *Call disconnect event
			 */
			include f( "system/callables/event.php" );
			internalMessage( "Disconnected!!!!" );
			$dAmn->reconnect();
		}
	}
}//this is the end of the while loop.
//EOF n00bs : )
?>
