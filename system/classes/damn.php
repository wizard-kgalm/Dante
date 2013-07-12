<?php
/**
 * dAmn Class
 *
 *@author SubjectX52873M <SubjectX52873M@gmail.com>
 *@author electicnet
 *@copyright SubjectX52873M 2006
 *@version 0.9
 *@package Dante
 *@license http://www.opensource.org/licenses/gpl-license.php GNU General Public License
 */
/**
 * dAmn related functions
 * contains alot of functions and variables related to dAmn function.
 *@author SubjectX52873M <SubjectX52873M@gmail.com>
 *@author electicnet
 *@copyright SubjectX52873M 2006
 *@version 0.9
 */
class dAmn {
  /**
	 * Stores whether or not the bot is connected.
	 *@var bool $connected
	 */
	var $connected;
	/**
	 * Stores whether or not the bot is logged in.
	 *@var bool $loggedIn
	 */
	var $loggedIn;
	
	public $server = array(
		'chat' => array(
			'host' => 'chat.deviantart.com',
			'version' => '0.3',
			'port' => 3900,
		),
		'login' => array(
			'transport' => 'ssl://',
			'host' => 'www.deviantart.com',
			'file' => '/users/login',
			'port' => 443,
		),
	);
	/**
	 * Use the stored token for login.
	 *
	 *This is to track the use of a token and whether a new one is required
	 *@var bool $UseToken
	 */
	var $UseToken;

	/**
	 *Last access time for the socket
	 *@var integer $lastAccess
	 */
	var $lastAccess;

	/**
	 *Number of disconnections for current session
	 *@var integer $disconnectCount
	 */
	var $disconnectCount;

	/**
	 *Number of disconnections for current session
	 *@var string $data
	 */
	private $data;
	/**
	 *Stores room data
	 *
	 *Values in this array are room objects
	 *@see room
	 *@var array $room
	 */
	public $room;

	/**
	 * Bot Startup
	 *
	 * Runs dAmn::getAuthToken() and sets $config['bot']['token']
	 *@return void
	 *@author electricnet
	 *@author SubjectX52873M <SubjectX52873M@gmail.com>
	 *@version 0.9
	 */
	function init(){
		global $config, $seperator, $debugLevel;
		$this->connected = FALSE;
		$this->loggedIn = FALSE;
		//Grab login info.
		internalHeader( "STARTING DANTE", " = " );
		intMsg( "Running " . BotType . " version " . BotVersion . " " . BotState . ( ( BotRevision != '' ) ? ( " revision " . BotRevision ) : ( '' ) ) );
		if( debug( FALSE,1 ) ) intMsg( "Debug level: $debugLevel" );
		internalMessage( "Written by: SubjectX52873M." );
		internalHeader( "AUTHTOKEN" );
		//New to version 0.3, now get the AuthToken
		if ( $this->UseToken ) {
			internalMessage( "Trying Stored token first." );
		} else {
			$config['bot']['token'] = $this->getAuthToken();
			save_config( 'bot' );
		}
	}
	/**
	 * If the bot fails to load properly you need to reconnect the socket.
	 *
	 *This is only called if the stored Token fails to work.
	 *@author SubjectX52873M <SubjectX52873M@gmail.com>
	 *@return void
	 *@version 0.5
	 */
	function reconSocket() {
		global $config, $socket;
		socket_close( $socket );
		$socket = NULL;
		$socket = socket_create( AF_INET, SOCK_STREAM, SOL_TCP );
		if( !@socket_connect( $socket, gethostbyname( BotServer ), BotPort ) ) {
			intMsg( 'Can\'t connect to the server!' );
			return;
		}
		if ( $this->UseToken == FALSE ) {
			//Okay, function called to reconnect from a failure with a stored token.
			internalMessage( "Failed to login using stored token. Grabbing a new one and trying again." );
			$config['bot']['token'] = $this->getAuthToken();
			save_config( 'bot' );
			global $packetping;
			$packetping = microtime( TRUE );
			//Connect
			$this->startup();
			//Join rooms.
			$this->autoJoin();
			$this->pingpong();
			$loop = 0;
			$onceconnected = FALSE;
		}
	}

	/**
	 *Reconnect on disconnect
	 *
	 *Makes the code in the main file cleaner to use this here.
	 *@return void
	 *@version 0.5
	 */
	function reconnect() {
		global $socket, $loops,$config;
		@socket_shutdown( $socket );
		@socket_close( $socket ); //Supress Errors.
		$socket = null;
		internalMessage( "Waiting 15 secs to reconnect." );
		sleep( 0 );
		// Remove previous references
		$socket = socket_create( AF_INET, SOCK_STREAM, SOL_TCP );
		//Connect the socket
		if( @socket_connect( $socket, gethostbyname( BotServer ), BotPort ) ) {
			internalmessage( "Connecting..." );
			$this->startup();
			$this->autoJoin();
			$loops = 0;
			global $packetping;
			$packetping = microtime( TRUE );
		} else {
			//Prevent inifinite loops
			$loops++;
			if ( $loops == $config['bot']['reconAttempts'] && $config['bot']['reconAttempts'] != 0 ) {
				intMsg( "Can't connect after 100 tries, dying." );
				die();
			}
			intMsg( "Can't connect to the server! Try: $loops" );
		}
	}
	/**
	 * Bot Startup
	 *
	 * Runs dAmn:connect() and dAmn::login()
	 *
	 * electricnet: Includes everything needed to startup in ONE function
	 *@author electricnet
	 *@return void
	 *@see dAmn:login()
	 *@see dAmn:connect()
	 *@version 0.1
	 */
	function startup() {
		global $seperator;
		internalHeader( "CONNECT" );
		$this->connect();
		$this->login();
	}
	/**
	 * Connect to server and send the useragent
	 *
	 *User Agent is currently just "agent = Dante"
	 *
	 *Raw dAmnClient 0.2\nagent = Dante\n\0
	 *@author electricnet
	 *@author SubjectX52873M <SubjectX52873M@gmail.com>
	 *@return void
	 *@version 0.1
	 */
	function connect() {
		global $running,$config;
		$this->send( "dAmnClient 0.2\nagent=" . BotAgent . "\n" .
						"owner=" . $config['bot']['owner'] . "\n" .
						"trigger=" . $config['bot']['trigger'] . "\n" .
						"creator=SubjectX52873M\n" . chr( 0 ) );
		$this->connected = TRUE;
		$running = TRUE;
	}
	/**
	 * Login to server and send the username and token
	 *@author electricnet
	 *@author SubjectX52873M <SubjectX52873M@gmail.com>
	 *@return void
	 *@version 0.1
	 */
	function login() {
		global $config;
		if( $config['bot']['token'] === FALSE ){
			die( "No authtoken, bad password?" );
		}
		if( $this->connected ){
			$this->send(
				"login " . $config['bot']['username'] . 
				"\npk=" . $config['bot']['token'] . 
				"\n" . chr( 0 ), TRUE );
			$this->loggedIn = TRUE;
		}
	}
	/**
	 * Send stuff to the server.
	 *
	 * It's sent raw, you need to add chr( 0 ) to the end of any data you send using this function.
	 *
	 *New in 0.7, message over 7000 bytes and truncated
	 *@author electricnet
	 *@return void
	 *@version 0.7
	 */
	function send( $data, $boic = FALSE ) {
		global $socket;
		if ( strlen( $data ) >= 32725 ) {
			$data = substr( $data, 0, 32739 );
			$data .= " [Cut]" . chr( 0 );
			intMsg( 'Warning, attempted to send large message.' );
		}
		$this->checkQueues();
		if( debug( FALSE, 5 ) ) {
			echo "** OUTGOING -> " . time() . " **\n";
			var_dump( $data );
		}

		if ( debug( FALSE,5 ) ) {
			socket_send( $socket, $data, strlen( $data ), 0x0 );
		} else {
			@socket_send( $socket, $data, strlen( $data ), 0x0 );
		}
		switch( socket_last_error( $socket ) ){
			case 0:
				break;
			case 10052:
			case 10053:
				intMsg( "Socket Error: Connection Reset" );
				$this->connected = FALSE;
				break;
			case 10054:
				intMsg("Socket forcibly closed remote connection - attempting reconnect");
				$this->connected=false;
				$this->reconSocket();
				break;
			default:
				intmsg( "Socket Error (" . socket_last_error( $socket ) . ") " . socket_strerror( socket_last_error( $socket ) ) );
		}
		socket_clear_error( $socket );

		$this->checkQueues();
	}

	/**
	 *Autojoin Rooms.
	 *@return void
	 *@version 0.4
	 */
	function autoJoin() {
		global $config;
		foreach( $config['bot']['rooms'] as $room ){
			$this->joinRoom( $room );
		}
	}
	/**
	 * Gets data from the socket and returns it
	 *@return string
	 *@version 0.9
	 */
	function listen() {
		global $socket;
		if( !is_resource( $socket ) ) {
			return array( FALSE, "Socket not resource." );
		}
		$response = "";
		if ( debug( FALSE,5 ) ) {
			if( 0 === socket_recv( $socket, $response, 8192, 0 ) ) $this->connected = FALSE;
		} else {
			if( 0 === @socket_recv( $socket, $response, 8192, 0 ) ) $this->connected = FALSE;
		}
		if ( socket_last_error( $socket ) != 0 ) {
			$x = array( socket_last_error( $socket ), socket_strerror( socket_last_error( $socket ) ) );
			socket_clear_error( $socket );
			return $x;

		}
		return $response;
	}
	/**
	 * PONG!
	 *
	 * Sends data out to check if the bot is still connected
	 *
	 * Modified in Version 0.4 to allow the main loop to use with out halting the bot.
	 *
	 * Used to repond to a ping from the dAmnserver
	 *@author electricnet
	 *@return void
	 *@version 0.6
	 */
	function pingpong() {
		$this->send( "pong\n" . chr( 0 ) );
	}

	/**
	 *Sends an invalid msg main packet to the server to check for a connection
	 *
	 *This should not be called too often. :)
	 *@author SubjectX52873M <SubjectX52873M@gmail.com>
	 *@return void
	 *@version 0.4
	 */
	function pingpong2() {
		$this->send( "send chat:!err_plz!\n\nmsg main\n\nBAD FOO" . chr( 0 ) );
	}
	/**
	 * Send a message to a the chatroom
	 *
	 * New in 0.5, Now accepts /np for nonparsed messages
	 *@author electricnet
	 *@return void
	 *@version 0.5
	 */
	function say( $message, $chatroom ) {
		global $config;
		$chatroom = generateChatName( $chatroom );
		if(!empty($config['symbols'])){
			$oi = $config['symbols']['on'];
		}
		$caps = $config['caps']['on'];
		$cosby = $config['cosby']['on'];
		 
		if(strtolower($chatroom) !== "chat:datashare"){
			if($caps){
				$message = strtoupper($message);
				$message = str_ireplace(":DEV" , ":dev",$message);
				$message = str_ireplace(":ICON", ":icon",$message);
				$message = str_ireplace(":THUMB", ":thumb",$message);
				$message = str_ireplace("&#X", "&#x",$message);
			}
			if($cosby){
				/*$alph = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z',);
				$cosbyalph = array("HIP", "DIP", "SQUOO", "BADA", "MEEP", "BLOO", "CAW", "SQEE", "WOOBLY", "BADUM", "LOO", "DERP", "NERP", "SPEE", "PAPA", "MOOM", "DUB", "PA", "NAW", "KA", "MIM", "ZAP", "NUP", "NA", "YEE", "ZOOP", "hip", "dip", "squoo", "bada", "meep", "bloo", "caw", "sqee", "woobly", "badum", "loo", "derp", "nerp", "spee", "papa", "moom", "dub", "pa", "naw", "ka", "mim", "zap", "nup", "na", "yee", "zoop");*/
				$cosbyalph = array(
				"a"=>"hip", "A"=>"HIP",
				"b"=>"dip", "B"=>"DIP",
				"c"=>"squoo", "C"=>"SQUOO",
				"d"=>"bada", "D"=>"BADA",
				"e"=>"meep", "E"=>"MEEP",
				"f"=>"bloo", "F"=>"BLOO",
				"g"=>"caw", "G"=>"CAW",
				"h"=>"sqee", "H"=>"SQEE",
				"i"=>"woobly","I"=>"WOOBLY",
				"j"=>"badum", "J"=>"BADUM",
				"k"=>"loo", "K"=>"LOO",
				"l"=>"derp", "L"=>"DERP",
				"m"=>"nerp", "M"=>"NERP",
				"n"=>"spee", "N"=>"SPEE",
				"o"=>"papa", "O"=>"PAPA",
				"p"=>"moom", "P"=>"MOOM",
				"q"=>"dub", "Q"=>"DUB",
				"r"=>"pa", "R"=>"PA",
				"s"=>"naw", "S"=>"NAW",
				"t"=>"ka", "T"=>"KA",
				"u"=>"mim", "U"=>"MIM",
				"v"=>"zap", "V"=>"ZAP",
				"w"=>"nup", "W"=>"NUP",
				"x"=>"na", "X"=>"NA",
				"y"=>"yee", "Y"=>"YEE",
				"z"=>"zoop", "Z"=>"ZOOP",
				"'"=>"'", "\""=>"\"", "`"=>"`", ":"=>":", "!"=>"!", "."=>".", ","=>",", "?"=>"?", "<"=>"<", ">"=>">", "@"=>"@", "#"=>"#", "$"=>"$", "%"=>"%",
				"*"=>"*", "("=>"(", ")"=>")", "_"=>"_", "-"=>"-", "+"=>"+", "="=>"=", "{"=>"{", "}"=>"}", "["=>"[", "]"=>"]", "\\"=>"\\", "/"=>"/", "~"=>"~",
				";"=>";", "|"=>"|", "^"=>"^", "1"=>"1", "2"=>"2", "3"=>"3", "4"=>"4", "5"=>"5", "6"=>"6", "7"=>"7", "8"=>"8", "9"=>"9", "0"=>"0",
				);
				$cspeak = explode( " ", $message );
				foreach($cspeak as $num => $words){
					$cspeak2 = str_split($cspeak[$num]);
					foreach($cspeak2 as $key => $Letter){
						$cspeak2[$key] = $cosbyalph[$Letter];
						$message2 .= $cspeak2[$key];
					}
					$message2 .= " ";	
				}
				
				$message = $message2;
				/*$message = str_split($message);
				foreach($message as $Key => $Letter) {
				  $message[$Key] = $cosbyalph[$Letter];
				}
				$message = implode("", $message);
				*/
				
			}
			if($oi){
				$regalph = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w'/*,'x'*/,'y','z',);
				$symalph = array('&#x023A;','&#x0243;','&#x20A1;','&#x0189;','&#x0246;','&#x0191;','&#x01E4;','&#x04C7;','&#x1F3F;','&#x0248;','&#x0198;','&#x0141;','&#x04CD;','&#x0220;','&#x01FE;','&#x2C63;','Q','&#x2C64;','&#x015C;','&#x01AE;','&#x0244;','&#x01B2;','&#x20A9;','&#x04FE;','&#x0194;','&#x01B5;','&#x1D8F;','&#x1D6C;','&#x023C;','&#x0256;','&#x0247;','&#x0192;','&#x01E5;','&#x0267;','&#x0268;','&#x0284;','&#x0199;','&#x026B;','&#x0271;','&#x0273;','&#x01FF;','&#x01A5;','q','&#x027D;','&#x0282;','&#x0288;','&#x1D7E;','&#x028B;','&#x2C73;'/*,'&#x04FD;'*/,'&#x0263;','&#x0240;',);
				$firstpart = array('<&#x0282;&#x1D7E;&#x01A5;>','</&#x0282;&#x1D7E;&#x01A5;>','<&#x0282;&#x1D7E;&#x1D6C;>','</&#x0282;&#x1D7E;&#x1D6C;>','<&#x1D7E;>','</&#x1D7E;>','<&#x1D6C;>','</&#x1D6C;>','<&#x0268;>','<&#x0268;>','<&#x1D6C;&#x027D;>','<&#x1D6C;&#x027D;/>','<&#x1D8F;&#x1D6C;&#x1D6C;&#x027D; &#x0288;&#x0268;&#x0288;&#x026B;&#x0247;=','</&#x1D8F;&#x1D6C;&#x1D6C;&#x027D;>','&#x03Ƀ1;','&#x014Ƀ;','&#x025Ƀ;',);
				$secondpart = array('<sup>','</sup>','<sub>','</sub>','<u>','</u>','<b>','</b>','<i>','</i>','<br>','<br/>','<abbr title=','</abbr>','&#x03B1;','&#x014B;','&#x025B;',);
			}
			$reverse = $config['reverse']['on'];
			if($reverse){
				$message = "&#8238;".$message;
			}
		}
		if( substr( $message, 0, 4 ) == "/me " ){

			$this->me( substr( $message, 4 ), $chatroom );
		} elseif( substr( $message, 0, 4 ) == "/np " ){
			$this->npmsg( substr( $message, 4 ), $chatroom );
		} else {
			$message = str_replace($alph, $cosbyalph, $message);
			$message = str_replace($regalph, $symalph, $message);
			$message = str_replace ($firstpart, $secondpart, $message);
			$message = print_r( $message, TRUE);
			$this->send( "send " . $chatroom . "\n\nmsg main\n\n" . $message . chr( 0 ), TRUE );
		}
	}

	/**
	 * Send a nonparsed message to a chatroom
	 *
	 *@author SubjectX52873M <SubjectX52873M@gmail.com>
	 *@return void
	 *@version 0.3
	 */
	function npmsg( $message, $chatroom ) {
		global $config;
		$oi = $config['symbols']['on'];
		$caps = $config['caps']['on'];
		$cosby = $config['cosby']['on'];
		 
		if(strtolower(generateChatName($chatroom)) !== "chat:datashare"){
			if($caps){
				$message = strtoupper($message);
				$message = str_ireplace(":DEV" , ":dev",$message);
				$message = str_ireplace(":ICON", ":icon",$message);
				$message = str_ireplace(":THUMB", ":thumb",$message);
				$message = str_ireplace("&#X", "&#x",$message);
			}
			if($cosby){
				$cosbyalph = array(
				"a"=>"hip", "A"=>"HIP",
				"b"=>"dip", "B"=>"DIP",
				"c"=>"squoo", "C"=>"SQUOO",
				"d"=>"bada", "D"=>"BADA",
				"e"=>"meep", "E"=>"MEEP",
				"f"=>"bloo", "F"=>"BLOO",
				"g"=>"caw", "G"=>"CAW",
				"h"=>"sqee", "H"=>"SQEE",
				"i"=>"woobly","I"=>"WOOBLY",
				"j"=>"badum", "J"=>"BADUM",
				"k"=>"loo", "K"=>"LOO",
				"l"=>"derp", "L"=>"DERP",
				"m"=>"nerp", "M"=>"NERP",
				"n"=>"spee", "N"=>"SPEE",
				"o"=>"papa", "O"=>"PAPA",
				"p"=>"moom", "P"=>"MOOM",
				"q"=>"dub", "Q"=>"DUB",
				"r"=>"pa", "R"=>"PA",
				"s"=>"naw", "S"=>"NAW",
				"t"=>"ka", "T"=>"KA",
				"u"=>"mim", "U"=>"MIM",
				"v"=>"zap", "V"=>"ZAP",
				"w"=>"nup", "W"=>"NUP",
				"x"=>"na", "X"=>"NA",
				"y"=>"yee", "Y"=>"YEE",
				"z"=>"zoop", "Z"=>"ZOOP",
				"'"=>"'", "\""=>"\"", "`"=>"`", ":"=>":", "!"=>"!", "."=>".", ","=>",", "?"=>"?", "<"=>"<", ">"=>">", "@"=>"@", "#"=>"#", "$"=>"$", "%"=>"%",
				"*"=>"*", "("=>"(", ")"=>")", "_"=>"_", "-"=>"-", "+"=>"+", "="=>"=", "{"=>"{", "}"=>"}", "["=>"[", "]"=>"]", "\\"=>"\\", "/"=>"/", "~"=>"~",
				";"=>";", "|"=>"|", "^"=>"^", "1"=>"1", "2"=>"2", "3"=>"3", "4"=>"4", "5"=>"5", "6"=>"6", "7"=>"7", "8"=>"8", "9"=>"9", "0"=>"0",
				);
				$cspeak = explode( " ", $message );
				foreach($cspeak as $num => $words){
					$cspeak2 = str_split($cspeak[$num]);
					foreach($cspeak2 as $key => $Letter){
						$cspeak2[$key] = $cosbyalph[$Letter];
						$message2 .= $cspeak2[$key];
					}
					$message2 .= " ";	
				}
				
				$message = $message2;
				/*$message = str_split($message);
				foreach($message as $Key => $Letter) {
				  $message[$Key] = $cosbyalph[$Letter];
				}
				$message = implode("", $message);
				*/
			}	
		if($oi){
			$regalph = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w'/*,'x'*/,'y','z',);
			$symalph = array('&#x023A;','&#x0243;','&#x20A1;','&#x0189;','&#x0246;','&#x0191;','&#x01E4;','&#x04C7;','&#x1F3F;','&#x0248;','&#x0198;','&#x0141;','&#x04CD;','&#x0220;','&#x01FE;','&#x2C63;','Q','&#x2C64;','&#x015C;','&#x01AE;','&#x0244;','&#x01B2;','&#x20A9;','&#x04FE;','&#x0194;','&#x01B5;','&#x1D8F;','&#x1D6C;','&#x023C;','&#x0256;','&#x0247;','&#x0192;','&#x01E5;','&#x0267;','&#x0268;','&#x0284;','&#x0199;','&#x026B;','&#x0271;','&#x0273;','&#x01FF;','&#x01A5;','q','&#x027D;','&#x0282;','&#x0288;','&#x1D7E;','&#x028B;','&#x2C73;'/*,'&#x04FD;'*/,'&#x0263;','&#x0240;',);
			$firstpart = array('<&#x0282;&#x1D7E;&#x01A5;>','</&#x0282;&#x1D7E;&#x01A5;>','<&#x0282;&#x1D7E;&#x1D6C;>','</&#x0282;&#x1D7E;&#x1D6C;>','<&#x1D7E;>','</&#x1D7E;>','<&#x1D6C;>','</&#x1D6C;>','<&#x0268;>','<&#x0268;>','<&#x1D6C;&#x027D;>','<&#x1D6C;&#x027D;/>','<&#x1D8F;&#x1D6C;&#x1D6C;&#x027D; &#x0288;&#x0268;&#x0288;&#x026B;&#x0247;=','</&#x1D8F;&#x1D6C;&#x1D6C;&#x027D;>','&#x03Ƀ1;','&#x014Ƀ;','&#x025Ƀ;',);
			$secondpart = array('<sup>','</sup>','<sub>','</sub>','<u>','</u>','<b>','</b>','<i>','</i>','<br>','<br/>','<abbr title=','</abbr>','&#x03B1;','&#x014B;','&#x025B;',);
		}
	}
		$message = str_replace($regalph, $symalph, $message);
		$message = str_replace ($firstpart, $secondpart, $message);
		$message = print_r( $message, TRUE );
		$chatroom = generateChatName( $chatroom );
		$this->send( "send " . $chatroom . "\n\nnpmsg main\n\n" . $message . chr( 0 ) );
	}
	/**
	 * Send an action to a the chatroom
	 *@author electricnet
	 *@return void
	 *@version 0.1
	 */
	function me( $message, $chatroom ) {
		global $config;
		$oi = $config['symbols']['on'];
		$cosby = $config['cosby']['on'];
		$caps = $config['caps']['on'];
		if(strtolower(generateChatName($chatroom)) !== "chat:datashare"){
			if($caps){
				$message = strtoupper($message);
				$message = str_ireplace(":DEV" , ":dev",$message);
				$message = str_ireplace(":ICON", ":icon",$message);
				$message = str_ireplace(":THUMB", ":thumb",$message);
				$message = str_ireplace("&#X", "&#x",$message);
			}
			if($cosby){
				/*$alph = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z',);
				$cosbyalph = array("HIP", "DIP", "SQUOO", "BADA", "MEEP", "BLOO", "CAW", "SQEE", "WOOBLY", "BADUM", "LOO", "DERP", "NERP", "SPEE", "PAPA", "MOOM", "DUB", "PA", "NAW", "KA", "MIM", "ZAP", "NUP", "NA", "YEE", "ZOOP", "hip", "dip", "squoo", "bada", "meep", "bloo", "caw", "sqee", "woobly", "badum", "loo", "derp", "nerp", "spee", "papa", "moom", "dub", "pa", "naw", "ka", "mim", "zap", "nup", "na", "yee", "zoop");*/
				$cosbyalph = array(
				"a"=>"hip", "A"=>"HIP",
				"b"=>"dip", "B"=>"DIP",
				"c"=>"squoo", "C"=>"SQUOO",
				"d"=>"bada", "D"=>"BADA",
				"e"=>"meep", "E"=>"MEEP",
				"f"=>"bloo", "F"=>"BLOO",
				"g"=>"caw", "G"=>"CAW",
				"h"=>"squee", "H"=>"SQUEE",
				"i"=>"woobly","I"=>"WOOBLY",
				"j"=>"badum", "J"=>"BADUM",
				"k"=>"loo", "K"=>"LOO",
				"l"=>"derp", "L"=>"DERP",
				"m"=>"nerp", "M"=>"NERP",
				"n"=>"spee", "N"=>"SPEE",
				"o"=>"papa", "O"=>"PAPA",
				"p"=>"moom", "P"=>"MOOM",
				"q"=>"dub", "Q"=>"DUB",
				"r"=>"pa", "R"=>"PA",
				"s"=>"naw", "S"=>"NAW",
				"t"=>"ka", "T"=>"KA",
				"u"=>"mim", "U"=>"MIM",
				"v"=>"zap", "V"=>"ZAP",
				"w"=>"nup", "W"=>"NUP",
				"x"=>"na", "X"=>"NA",
				"y"=>"yee", "Y"=>"YEE",
				"z"=>"zoop", "Z"=>"ZOOP",
				"'"=>"'", "\""=>"\"", "`"=>"`", ":"=>":", "!"=>"!", "."=>".", ","=>",", "?"=>"?", "<"=>"<", ">"=>">", "@"=>"@", "#"=>"#", "$"=>"$", "%"=>"%",
				"*"=>"*", "("=>"(", ")"=>")", "_"=>"_", "-"=>"-", "+"=>"+", "="=>"=", "{"=>"{", "}"=>"}", "["=>"[", "]"=>"]", "\\"=>"\\", "/"=>"/", "~"=>"~",
				";"=>";", "|"=>"|", "^"=>"^", "1"=>"1", "2"=>"2", "3"=>"3", "4"=>"4", "5"=>"5", "6"=>"6", "7"=>"7", "8"=>"8", "9"=>"9", "0"=>"0",
				);
				$cspeak = explode( " ", $message );
				foreach($cspeak as $num => $words){
					$cspeak2 = str_split($cspeak[$num]);
					foreach($cspeak2 as $key => $Letter){
						$cspeak2[$key] = $cosbyalph[$Letter];
						$message2 .= $cspeak2[$key];
					}
					$message2 .= " ";	
				}
				
				$message = $message2;
				/*$message = str_split($message);
				foreach($message as $Key => $Letter) {
				  $message[$Key] = $cosbyalph[$Letter];
				}
				$message = implode("", $message);
				*/
			}
		
	if($oi){
		$regalph = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w'/*,'x'*/,'y','z',);
		$symalph = array('&#x023A;','&#x0243;','&#x20A1;','&#x0189;','&#x0246;','&#x0191;','&#x01E4;','&#x04C7;','&#x1F3F;','&#x0248;','&#x0198;','&#x0141;','&#x04CD;','&#x0220;','&#x01FE;','&#x2C63;','Q','&#x2C64;','&#x015C;','&#x01AE;','&#x0244;','&#x01B2;','&#x20A9;','&#x04FE;','&#x0194;','&#x01B5;','&#x1D8F;','&#x1D6C;','&#x023C;','&#x0256;','&#x0247;','&#x0192;','&#x01E5;','&#x0267;','&#x0268;','&#x0284;','&#x0199;','&#x026B;','&#x0271;','&#x0273;','&#x01FF;','&#x01A5;','q','&#x027D;','&#x0282;','&#x0288;','&#x1D7E;','&#x028B;','&#x2C73;'/*,'&#x04FD;'*/,'&#x0263;','&#x0240;',);
		$firstpart = array('<&#x0282;&#x1D7E;&#x01A5;>','</&#x0282;&#x1D7E;&#x01A5;>','<&#x0282;&#x1D7E;&#x1D6C;>','</&#x0282;&#x1D7E;&#x1D6C;>','<&#x1D7E;>','</&#x1D7E;>','<&#x1D6C;>','</&#x1D6C;>','<&#x0268;>','<&#x0268;>','<&#x1D6C;&#x027D;>','<&#x1D6C;&#x027D;/>','<&#x1D8F;&#x1D6C;&#x1D6C;&#x027D; &#x0288;&#x0268;&#x0288;&#x026B;&#x0247;=','</&#x1D8F;&#x1D6C;&#x1D6C;&#x027D;>','&#x03Ƀ1;','&#x014Ƀ;','&#x025Ƀ;',);
			$secondpart = array('<sup>','</sup>','<sub>','</sub>','<u>','</u>','<b>','</b>','<i>','</i>','<br>','<br/>','<abbr title=','</abbr>','&#x03B1;','&#x014B;','&#x025B;',);
			}
	}
		$message = str_replace($regalph, $symalph, $message);
		$message = str_replace ($firstpart, $secondpart, $message);
		$message = print_r( $message, TRUE );
		$chatroom = generateChatName( $chatroom );
		$this->send( "send " . $chatroom . "\n\naction main\n\n" . $message . chr( 0 ) );
	}
	/**
	 * Join a chatroom
	 *@author electricnet
	 *@return bool TRUE for success in joining, FALSE for failure.
	 *@return void
	 *@version 0.1
	 */
	function joinRoom( $chatroom ) {
		if( !empty( $chatroom ) ){
			$chatroom = generateChatName( $chatroom );
			$this->send( "join " . $chatroom . "\n" . chr( 0 ), TRUE );
			return TRUE;
		} else {
			return FALSE;
		}
	}
	/**
	 * Leave a chatroom
	 *@author electricnet
	 *@return bool TRUE for success in leave, FALSE for failure.
	 *@return void
	 *@version 0.1
	 */
	function partRoom( $chatroom ) {
		if( !empty( $chatroom ) ) {
			$chatroom = generateChatName( $chatroom );
			$this->send( "part " . $chatroom . "\n" . chr( 0 ) );
			return TRUE;
		} else {
			return FALSE;
		}
	}
	/**
	 * Get a property
	 *
	 * This does not return a value. It sends a request for data to the server and dAmn:process catches it later.
	 *@author electricnet
	 *@return void
	 *@version 0.1
	 */
	function get( $property, $chatroom ) {
		$chatroom = generateChatName( $chatroom );
		if ( $chatroom == FALSE )return;
		$this->send( "get " . $chatroom . "\np=" . $property . "\n" . chr( 0 ) );
	}

	/**
	 * /whois a user
	 *
	 * This does not return a value. It sends a request for data to the server and dAmn:process catches it later.
	 *@author electricnet
	 *@return void
	 *@version 0.4
	 */
	function getUserInfo( $username ) {
		if ( !empty( $username ) ) {
			$this->send( "get login:" . $username . "\np=info\n" . chr( 0 ) );
		}
	}

	/**
	 * Kick a user from a chatroom
	 *@author electricnet
	 *@origin Tarbot 0.2e
	 *@return void
	 */
	function kick( $username, $chatroom, $reason = "" ) {
		$chatroom = generateChatName( $chatroom );
		if ( $chatroom == FALSE ) return;
		$this->send( "kick " . $chatroom . "\nu=" . $username . "\n\n" . $reason . chr( 0 ) );
	}

	/**
	 * Promote a user.
	 *@author electricnet
	 *@return void
	 *@version 0.4
	 */
	function promote( $username, $privclass, $chatroom ) {
		$chatroom = generateChatName( $chatroom );
		if ( $chatroom == FALSE ) return;
		$this->send( "send " . $chatroom . "\n\npromote " . $username . "\n\n" . $privclass . chr( 0 ) );
	}
	/**
	 * Demote a user.
	 *@author electricnet
	 *@return void
	 *@version 0.4
	 */
	function demote( $username, $privclass, $chatroom ) {
		$chatroom = generateChatName( $chatroom );
		if ( $chatroom == FALSE ) return;
		$this->send( "send " . $chatroom . "\n\ndemote " . $username . "\n\n" . $privclass . chr( 0 ) );
	}
	/**
	 * Ban a user.
	 *
	 * Equiv to !demote (user) banned
	 *@author electricnet
	 *@return void
	 *@version 0.4
	 */
	function ban( $username, $chatroom ) {
		$chatroom = generateChatName( $chatroom );
		if ( $chatroom == FALSE ) return;
		$this->send( "send " . $chatroom . "\n\nban " . $username . "\n\n" . chr( 0 ) );
	}
	/**
	 * Unban a user.
	 *
	 *This promotes/demotes a user to the default guest level
	 *@author electricnet
	 *@return void
	 *@version 0.4
	 */
	function unban( $username, $chatroom ){
		$chatroom = generateChatName( $chatroom );
		if ( $chatroom == FALSE ) return;
		$this->send( "send " . $chatroom . "\n\nunban " . $username . "\n\n" . chr( 0 ) );
	}

	/**
	 * On December 11, 2006 (Central Standard Time)
	 * 16:41:50 <electricnet> :shrug:
	 * 16:41:51 <electricnet> Also
	 * 16:41:53 ** SubjectX52873M has been made a member of The-Bot-Team by electricnet *
	 * 16:42:08 * SubjectX52873M is overjoyed
	 */

	/**
	 * Set a property
	 *
	 * Only Topic/Title works right now.
	 *@author electricnet
	 *@return bool TRUE for success in leave, FALSE for failure.
	 *@version 0.4
	 */
	function set( $property, $value, $chatroom ) {
		$chatroom = generateChatName( $chatroom );
		if ( $chatroom == FALSE ) return FALSE;
		if( $property == "title" || $property == "topic" ){
			$this->send( "set " . $chatroom . "\np=" . $property . "\n\n" . $value . chr( 0 ) );
			return TRUE;
		} else {
			return FALSE;
		}
	}
	/**
	 * Admin
	 *
	 *See the dAmn FAQ for /admin uage
	 *@author electricnet
	 *@return void
	 *@version 0.4
	 */
	function admin( $command, $chatroom ) {
		$chatroom = generateChatName( $chatroom );
		if ( $chatroom == FALSE ) return;
		$this->send( "send " . $chatroom . "\n\nadmin\n\n" . $command . chr( 0 ) );
	}

	/**
	 *Check Socket for Data
	 *
	 *Checks for data from the socket and sends the info where it needs to go
	 *@return void
	 *@version 0.5
	 */
	//This function originates from Noodlebot 3.0
	function checkQueues( $boic = FALSE ) {
		global $socket;
		$r = array( $socket );
		$w = NULL;
		$e = NULL;
		$x = socket_select( $r, $w, $e, 0 );
		if ( $x === FALSE ) {}
		elseif( $x > 0 ) {
			if ( in_array( $socket, $r ) ) {
				$data = $this->listen();
				if ( $data === null ) {
					return;
				} elseif ( is_array( $data ) ) {
					if( $data[0] == 10052 ) intMsg( "Socket Error: Connection Reset" );
					elseif( $data[0] == 10053 ) intMsg( "Socket Error: Connection Aborted." );
					else intMsg( "Socket Error ( " . $data[0] . " ): " . $data[1] );
					$this->connected = FALSE;
					return $data;
				}
				$this->data .= $data;
				$datas = explode( chr( 0 ), $this->data );
				$this->data = $datas[count( $datas ) - 1];
				unset( $datas[count( $datas ) - 1] );
				if( debug( FALSE,5 ) ){
					echo "** INCOMING <- " . time() . "**\n";
					var_dump( $datas );
				}
				foreach( $datas as $recievedData ){
					if( debug( FALSE, 5 ) ) echo "( " . strlen( $recievedData ) . " )\n";
					$this->process( $recievedData );
				}
				$this->lastAccess = microtime( TRUE );
			} else {
				if ( ( microtime( TRUE ) - $this->lastAccess ) < 100 ) {
					$this->connected = FALSE;
					$lastAccess->lastAccess = microtime( TRUE );
					echo "\nSOCKET TIMEOUT\n";
				}
			}
		}
	}

	/**
	 *Get Token
	 *
	 *This function logs the bot into it's account and steals the cookie from the server. The cookie contains an Md5 hash, called an AuthToken. The dAmn server (chat.deviantart.com:3900) uses the AuthTokens for login not passwords. So the bot needs to grab the Authtoken before logging into the chat server.
	 *@author SubjectX52873M <SubjectX52873M@gmail.com>
	 *@author electricnet
	 *@return mixed FALSE on errors, string(32) on success
	 *@version 0.10r93
	 */
	function getAuthToken() {
	$login = parse_ini_file( f( "config.ini" ) ); 
	$username = $login['username'];
	$pass = $login['password'];

                // Method to get the cookie! Yeah! :D
                // Our first job is to open an SSL connection with our host.
                $socket = @fsockopen(
                        $this->server['login']['transport'].$this->server['login']['host'],
                        $this->server['login']['port']
                );
                // If we didn't manage that, we need to exit!
                if($socket === false) {
                return array(
                        'status' => 2,
                        'error' => 'Could not open an internet connection');
                }
                // Fill up the form payload
                $POST = '&username='.urlencode($username);
                $POST.= '&password='.urlencode($pass);
                $POST.= '&remember_me=1';
                // And now we send our header and post data and retrieve the response.
                $response = $this->send_headers(
                    $socket,
                    $this->server['login']['host'],
                    $this->server['login']['file'],
                    "http://www.deviantart.com/users/rockedout",
                    $POST
                );
               
                // Now that we have our data, we can close the socket.
                fclose ($socket);
                // And now we do the normal stuff, like checking if the response was empty or not.
                if(empty($response))
                return array(
                        'status' => 3,
                        'error' => 'No response returned from the server'
                );
                if(stripos($response, 'set-cookie') === false)
                return array(
                        'status' => 4,
                        'error' => 'No cookie returned'
                    );
                // Grab the cookies from the header
                $response=explode("\r\n", $response);
                $cookie_jar = array();
                foreach ($response as $line)
                    if (strpos($line, "Set-Cookie:")!== false)
                        $cookie_jar[] = substr($line, 12, strpos($line, "; ")-12);

                // Using these cookies, we're gonna go to chat.deviantart.com and get
                // our authtoken from the dAmn client.
                if (($socket = @fsockopen("ssl://www.deviantart.com", 443)) == false)
                     return array(
                        'status' => 2,
                        'error' => 'Could not open an internet connection');

                $response = $this->send_headers(
                    $socket,
                    "chat.deviantart.com",
                    "/chat/Botdom",
                    "http://chat.deviantart.com",
                    null,
                    $cookie_jar
                );

                // Now search for the authtoken in the response
                $cookie = null;
                if (($pos = strpos($response, "dAmn_Login( ")) !== false)
                {
                    $response = substr($response, $pos+12);
                    $cookie = substr($response, strpos($response, "\", ")+4, 32);
                }
                else return array(
                    'status' => 4,
                    'error' => 'No authtoken found in dAmn client'
                );
                                  
                // Because errors still happen, we need to make sure we now have an array!
                if(!$cookie)
                return array(
                        'status' => 5,
                        'error' => 'Malformed cookie returned'
                );
                // We got a valid cookie!
                return $cookie;
                
        }
        
        function send_headers($socket, $host, $url, $referer, $post=null, $cookies=array())
        {
            try
            {
                $headers = "";
                if (isset($post))
                    $headers .= "POST {$url} HTTP/1.1\r\n";
                else $headers .= "GET {$url} HTTP/1.1\r\n";
                $headers .= "Host: {$host}\r\n";
                $headers .= "User-Agent: Penis\r\n";
                $headers .= "Referer: {$referer}\r\n";
                if ($cookies != array())
                    $headers .= "Cookie: ".implode("; ", $cookies)."\r\n";
                $headers .= "Connection: close\r\n";
                $headers .= "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*\/*;q=0.8\r\n";
                $headers .= "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7\r\n";
                $headers .= "Content-Type: application/x-www-form-urlencoded\r\n";
                if (isset($post))
                    $headers .= "Content-Length: ".strlen($post)."\r\n\r\n{$post}";
                else $headers .= "\r\n";
                $response = "";
                //echo "OUTGOING:\n\n$headers\n\n";
                fputs($socket, $headers);
                while (!@feof ($socket)) $response .= @fgets ($socket, 8192);
                return $response;
            }
            catch (Exception $e)
            {
                echo "Exception occured: ".$e->getMessage()."\n";
                return "";
            }
        }
	/**
	 *Timeout
	 *
	 *@return void
	 *@author SubjectX52873M <SubjectX52873M@gmail.com>
	 *@origin Dante 0.2
	 *@version 0.5
	 */
	function timeout() {
		global $packetping;
		if ( !isset( $packetping ) ) $packetping = microtime( TRUE );
		$ping = microtime( TRUE ) - $packetping;
		if ( $ping > 100 && $packetping !== NULL ) {
			intmsg( "Timeout detected" );
			$packetping = NULL;
			//$this->connected = FALSE;
		}
	}



	/**
	 * Process Symbol
	 *
	 * This accepts the symbol that sit in front of a deviant's name and returns the type as a string.
	 *
	 * Example: dAmn::symproc('~') returns "Member" and dAmn::symproc('*') returns "Subscriber"
	 *
	 * Example: dAmn::symproc('~SubjectX52873M') returns 'Unknown Type (~SubjectX52873M)'
	 * @author SubjectX52873M <SubjectX52873M@gmail.com>
	 * @return string
	 * @version 0.7
	 */
	function symproc( $symbol = '=' ){
		$symbols = array(
			'~' => 'Member',
			'-' => 'Shop Account',
			'*' => 'Subscriber',
			'=' => 'Official Beta Tester',
			'`' => 'Former Staff or Senior Member',
			'�' => 'Alumni Staff',
			'#' => 'Art Group Member',
			'@' => 'Shoutbox/dAmn Staff',
			':' => 'Premium Content Staff',
			'�' => 'Policy Enforcement Staff',
			'%' => 'deviantART Prints Staff',
			'+' => 'General Staff',
			'�' => 'Creative Staff',
			'^' => 'Gallery Director',
			'$' => 'Core Administrator',
			'!' => 'Banned User'
		 );
		 if( array_key_exists( $symbol, $symbols ) ) {
		 	return $symbols[$symbol];
		 } else {
		 	return 'Unknown Type ('.$symbol.')';
		 }
	}

	/**
	*Process
	*
	*This breaks up the packets from the socket at routes the commands and calls modules to handle them.
	*@author electricnet
	*@author SubjectX52873M <SubjectX52873M@gmail.com>
	*@return void
	*@version 0.8
	*/
	function process( $data ) { 
		global $parser, $config, $whoisroom, $latestwhois, $packetping; 
		$rawdata = $data;
		$packetping = microtime( TRUE );
		$params = $parser->breakPacket( $data );
		$event = FALSE;
		if( debug( FALSE, 4 ) ) {
			echo ts() . " ** PROCESS " . time() . " **\n";
			var_dump( $params ); 
		}
		switch( $params[0][0] ){
			case "error":
				intMsg( "Received unkown packet" );
				debug( $params[1], 3 );
				break;
			case "dAmnServer":
				internalMessage( "Connected to dAmnServer " . trim( $params[0][1] ) );
				$event = "handshake";
				break;
			case "disconnect":
				internalMessage( "Disconnected ( {$params[1]['e']} )" );
				break;
			case "login":
				if( $params[1]['e'] == "ok" ){
					internalMessage( "You are successfully logged in as " . $params[0][1] . "." );
					$this->UseToken = TRUE;
					$event = "login-success";
					include f( "system/callables/event.php" );
				} elseif( $params[1]['e'] == "authentication failed" ){
					if ( $this->UseToken ) {
						$this->UseToken = FALSE;
						$this->reconSocket();
						$this->connected = TRUE;
					} else {
						internalMessage( "Couldn't log in as {$params[0][1]}, authentication failed. Please check that your username and/or password is right." );
					}
				} else {
					internalMessage( "An unexpected error happened logging in as {$params[0][1]} Error recieved: \"{$params['1']['e']}\"" );
				}
				$event = "login";
				break;
			case "ping":
				$this->pingpong();
				echo "PING??? PONG!!!\n";
				$event = "ping";
				break;
			case "join":
				$c = $chatroom = rChatName( $params[0][1] );
				$cc = strtolower( rChatName( $chatroom ) );
				debug( "[{$c}] ** Joined!",0,2 );
				if( debug( FALSE, 3 ) ){ var_dump( $params ); }
				if( $params['1']['e'] == "ok" ){
					//New room class
					if( !isset( $this->room[$cc] ) ) $this->room[$cc] = new Room( $cc );
					if($c == "#DataShare"){
						DataShare('j');
					}
					$event = "join-success";
					include f( "system/callables/event.php" );
				} else {
					internalMessage( "Couldn't join $chatroom, got error: \"" . ucfirst( $params[1]['e'] ) . "\"." );
					$event = "join-fail";
					include f( "system/callables/event.php" );
				}
				$event = "join";
				break;
			case "kicked":
				$c = $chatroom = rChatName( $params[0][1] );
				$cc = strtolower( $chatroom );
				$by = $params[1][1];
				$message = $params[2];
				$output = "Kicked from $c by $by " . ( ( $message ) ? ( " ($message)" ) : ( "" ) );
				//Unset room object
				$thisRoom = clone $this->room[$cc];
				unset( $this->room[$cc] );
				registerChat( "** " . $output, $c );
				$event = "kicked";
				break;
			case "send":
				$c = $chatroom = rChatName( $params[0][1] );
				if ( $c == "#!err_plz!" ) break;
				$e = $params[1]['e'];
				if( debug( FALSE, 4 ) ) var_dump( $params );
				debug( "Couldn't send to $c ($e)",1 );
				$event = "send";
				break;
			case "get": //A whois was requested, but an error gets returned.
				if( debug( FALSE, 4 ) ) var_dump( $params );
				if( $params[1]['e'] == "bad namespace" ) {
					$message = "This user isn't currently on dAmn.";
				} else {
					$message = "An unexpected error happened to your whois query: \"" . $params[1]['e'] . "\""; 
				}
				if( !empty( $whoisroom ) ) {
					$this->say( $message, $whoisroom );
				}
				$event = "get";
				break;
			case "part":
				$c = $chatroom = rChatName( $params[0][1] );
				$cc = strtolower( rChatName( $chatroom ) );
				if( $params[1]['e'] == "ok" ){
					$output = "Parted $c.";
					registerChat( "** " . $output, $c );
					//$thisRoom = clone $this->room[$c];
					unset( $this->room[$cc] );
					$event = "part-success";
					include f( "system/callables/event.php" );
				} else {
					internalMessage( "Couldn't part " . $chatroom . ", got error: \"" . ucfirst( $params[1]['e'] ) . "\"." );
					$event = "part-fail";
					include f( "system/callables/event.php" );
				}
				$event = "part";
				break;
			case "recv":
				$c = $chatroom = rChatName( $params[0][1] );
				$cc = strtolower( rChatName( $chatroom ) );
				switch( $params[1][0] ){
					case "msg":
						global $config;
						$from = $params[2]['from'];
						$message = trim( $params[3] );
						if( pchatParse::isData( $message,$c ) && $from == $config['bot']['username'] ) {
							$info = pchatParse::$data;
							$c = $chatroom = pchatParse::$room;
							$event = "pchatParse";
							if(substr($message, 0, 4) == "DDS:") DataShare($from, $message, $c);
						} else {
							$output = "[{$c}] <{$from}> {$message}";
							if( !empty( $output ) ) registerChat( $output, $c );
							//$this->joinroom($cc);
							//$userdata = $this->room[$cc]->getUserData( $from );
							processMessage( $message, $params[2]['from'], $c, $params[1][0] );
							$event = "recv-msg";
							if($c == "#DataShare"){
								DataShare($from, $message);
							}
						}
						break;
					case "action":
						$from = $params[2]['from'];
						$message = trim( $params[3] );
						$output = "[{$c}] * {$from} {$message}";
						if( !empty( $output ) ){ registerChat( $output, $c ); }
						$userdata = $this->room[$cc]->getUserData( $from );
						processMessage( $message, $params[2]['from'], $c, $params[1][0] );
						$event = "recv-action";
						break;
					case "join":
						$from = $params[1][1];
						$output = "[{$c}] ** {$from} has joined";
						//Register user
						$count = $this->room[$cc]->userJoined( $from, $params[2] );
						$userdata = $this->room[$cc]->getUserData( $from );
						if( !empty( $output ) ){ registerChat( $output, $c ); 
						eLog( "Joined {$count} times", $c, FALSE );}
						$event = "recv-join";
						break;
					case "part":
						$output = "[{$c}] ** {$params[1][1]} has left" . ( ( !empty( $params[2]['r'] ) ) ? ( " ({$params[2]['r']})" ) : ( "" ) );
						$from = $params[1][1];
						//Unregister user
						$userdata = $this->room[$cc]->getUserData( $from );
						$count = $this->room[$cc]->userParted( $from );
						if( !empty( $output ) ){ eLog( "Joined {$count} times", $c, FALSE );
						registerChat( $output, $c );}
						$event = "recv-part";
						break;
					case "kicked":
						$from = $params[1][1];
						$by = $params[2]['by'];
						$message = $params[3];
						$output = "[{$c}] ** {$from} was kicked by {$by}" . ( ( !empty( $params[3] ) ) ? ( " ({$params[3]})" ) : ( "" ) );
						$userdata = $this->room[$cc]->getUserData( $from );
						$this->room[$cc]->userKicked( $from );
						if( !empty( $output ) ) registerChat( $output, $c );
						$event = "recv-kicked";
						break;
					case "privchg":
						$from = $params[1][1];
						$by = $params[2]['by'];
						$pc = $params[2]['pc'];
						$output = "[{$c}] ** {$from} has been made a member of '{$pc}' by {$by}";
						$this->room[$cc]->userPrivChange( $from,$pc );
						$userdata = $this->room[$cc]->getUserData( $from );
						if( !empty( $output ) ) registerChat( $output, $c );
						$event = "recv-privchg";
						break;
					case "admin":
						$event = "recv-admin";
						include f( "system/callables/event.php" );
						switch( $params[1][1] ){
							case "create":
								if( $params[2]['p'] == "privclass" ) {
									$by = $params[2]["by"];
									$pc = $params[2]["name"];
									$privs = $params[2]["privs"];
									$output = "[{$c}] ** Privilege class '{$pc}' has been created by {$by} with: {$privs}";
									if( !empty( $output ) ) registerChat( $output, $c );
								}
								$event = "recv-admin-create";
								break;
							case "update":
								if( $params[2]['p'] == "privclass" ) {
									$by = $params[2]["by"];
									$pc = $params[2]["name"];
									$privs = $params[2]["privs"];
									$output = "[{$c}] ** Privilege class '{$pc}' has been updated by {$by} with: {$privs}";
								}
								if( !empty( $output ) ) registerChat( $output, $c );
								$event = "recv-admin-update";
								break;
							case "rename":
								if( $params[2]['p'] == "privclass" ) {
									$by = $params[2]["by"];
									$pc = $params[2]["name"];
									$privs = $params[2]["privs"];
									$prev = $params[2]['prev'];
									$n = $params[2]['n'];
									$output = "[{$c}] ** Privilege class '{$prev}' has been renamed to '{$pc}' by {$by} -- " . intval( $n ) . " members were affected.";
									//Register with bot
									$this->room[$cc]->privRename( $prev, $pc );
								}
								if( !empty( $output ) ) registerChat( $output, $c );
								$event = "recv-admin-rename";
								break;
							case "move":
								if( $params[2]['p'] == "users" ) {
									$by = $params[2]["by"];
									$pc = $params[2]["name"];
									$privs = $params[2]["privs"];
									$prev = $params[2]['prev'];
									$n = $params[2]['n'];
									$output = "[{$c}] ** All members of '{$prev}' has been moved to '{$pc}' by {$by} -- " . intval( $n ) . " members were affected.";
								}
								$this->room[$cc]->moveUsers( $prev, $pc );
								if( !empty( $output ) ) registerChat( $output, $c );
								$event = "recv-admin-move";
								break;
							case "remove": 
								if( $params[2]['p'] == "privclass" ) {
									$by = $params[2]["by"];
									$pc = $params[2]["name"];
									$n = $params[2]['n'];
									$output = "[{$c}] ** Privilege class '{$prev}' has been removed by {$by} -- " . intval( $n ) . " members were affected.";
								}
								$this->room[$cc]->removePC( $pc );
								if( !empty( $output ) ){ registerChat( $output, $c ); }
								$event = "recv-admin-remove";
								break;
							case "show":
								global $adminroom;
								if( $params[2]['p'] == "privclass" ) {
									$adminroom = $c;
									$say = "<strong><u>Privclass list for {$c}</u></strong><br>";
									foreach( $params[3] as $pc => $privs ) {
										$say .= "<strong>$pc: </strong> $privs<br>";
										$config['testing'][$pc] = $privs;
										save_config("testing");
									}
									$this->say( "$say",$adminroom );
									$adminroom = '';
									$event = "recv-admin-show-privclass";
								} elseif( $params[2]['p'] == "users" ) {
									$adminroom = $c;
									$this->say( "<strong><u>Full user list for {$c}</u><strong>", $adminroom );
									foreach( $params[3] as $pc => $privs ){
										$this->say( "<strong>$pc</strong><br><sub><code>$privs", $adminroom );
									}
									$this->say( "<strong> === End of list === </strong>", $adminroom );
									$adminroom = '';
									$event = "recv-admin-show-users";
									$adminroom = '';
								} else {
									$event = "recv-admin-show-unknown";
								}
								$output = "[{$c}] Event: {$event}";
								if( !empty( $output ) ) registerChat( $output, $c );
								//Oo
								break;
							default:
								break;
						}
						break;
				}
				break;
			case "property":
				$c = $chatroom = rChatName( $params[0][1] );
				$cc = strtolower( rChatName( $chatroom ) );
				//event
				$event = "property";
				include f( "system/callables/event.php" );
				switch( $params[1]['p'] ) {
					case "privclasses":
						if( !isset( $this->room[$cc] ) ) $this->room[$cc] = new Room( $cc ); 
						$privclasses = $params[2];
						$this->room[$cc]->setPcs( $privclasses );
						debug( "[{$c}] ** Got privclasses.", 3 );
						$event = "property-privclasses";
						break;
					case "members":
						if( !isset( $this->room[$cc] ) ) $this->room[$cc] = new Room( $cc );
						eLog( "Members: " . implode( ", ", array_keys( $params[2] ) ), $chatroom, FALSE );
						
						$this->room[$cc]->userMembers( $params[2] );
						debug( "[{$c}] ** Got members.", 3 );
						$event = "property-members";
						break;
					case "title":
						$message = $params[2];
						$by = $params[1]['by'];
						$ts = $params[1]['ts'];
						if( !isset( $this->room[$cc] ) ) $this->room[$cc] = new Room( $cc );
						$this->room[$cc]->setTitle( $message, $by, $ts );
						if ( substr( $c, 0, 1 ) != "@" ) {
							$output = "[{$c}] ** Title changed by " . $params[1]['by'] . " on " . gmdate( "m/d/y H:i \G\M\T", $params[1]['ts'] );
							registerChat( $output, $chatroom );
							eLog( "Title: {$message}", $chatroom, FALSE );
						}
						$event = "property-title";
						break;
					case "topic":
						$message = $params[2];
						$by = $params[1]['by'];
						$ts = $params[1]['ts'];
						if( !isset( $this->room[$cc] ) ) $this->room[$cc] = new Room( $cc );
						$this->room[$cc]->setTopic( $message, $by, $ts );
						if ( substr( $c, 0, 1 ) != "@" ) {
							$output = "[{$c}] ** Topic changed by " . $params[1]['by'] . " on " . gmdate( "m/d/y H:i \G\M\T", $params[1]['ts'] );
							registerChat( $output, $chatroom );
							eLog( "Topic: {$message}", $chatroom, FALSE );
						}
						$event = "property-topic";
						break;
					case "info": 
						global $latestwhois, $whoisroom;
						$chatroom = $c = array_pop( $whoisroom );
						$latestwhois[$username] = $params;
						$latestwhois[$username]['updated'] = time();
						$event = "whois";
						break;
				}
				break;
		}
		//Call most events
		if( $event ) include f( "system/callables/event.php" );
	}
}?>