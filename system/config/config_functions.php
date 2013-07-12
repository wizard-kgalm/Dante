<?php
/**
* Configuration Functions
*
* Load/Save
*@author SubjectX52873M <SubjectX52873M@gmail.com>
*@copyright SubjectX52873M 2006
*@package Dante
*@license http://www.opensource.org/licenses/gpl-license.php GNU General Public License
*@version 0.9
*/
/**
* Load a Bot Save File or Variable Serialized Data File
*@return mixed File contents on success, fales on error
*/
function load_bsv( $file ) {
  if( file_exists( $file ) ) return unserialize( file_get_contents( $file ) );
	else return false;
}

/**
* Save a Bot Save File or Variable Serialized Data File
*@return bool
*/
function save_bsv( $file, $data ) {
	$f = fopen( $file, "w" );
	$return = fwrite( $f, serialize( $data ) );
	fclose( $f );
	return $return;
}
/**
*Futurism Function
*@return bool
*/
function saveData( $filename, $input, $serialize = TRUE ) {
	if( !is_bool( $serialize ) ) return FALSE;
	if( !is_dir( f( "savedata") ) ) mkdir( f( "savedata" ) );
	if( $serialize ) {
		$f = fopen( f( "savedata/" . $filename . ".bsv" ), "w" );
		fwrite($f, serialize( $input ) );
		fclose( $f );
		return true;
	} else {
		$f = fopen( f( "savedata/" . $filename . ".txt" ), "w" );
		fwrite( $f, str_replace( "\n", "\r\n", var_export( $input, true ) ) );
		fclose( $f );
		return true;
	}
	return false;
}
/**
* Futurism Function
*@return mixed
*/
function openData( $filename, $serialized = TRUE) {
	if( !is_bool( $serialized ) ) return false;
	if( !is_dir( f( "savedata" ) ) ) mkdir( f( "savedata" ) );
	if( $serialized ) {
		return unserialize( file_get_contents( f( "savedata/" . $filename . ".bsv" ) ) );
	} else {
		return file_get_contents( f( "savedata/" . $filename . ".txt" ) );
	}
	return false;
}

/**
* Load config from a vudf file
*@version 0.7
*@return void
*/
function load_config() {
	global $config, $seperator;
	$backupConfig = array();
	if( !isset( $config['bot']['timestamp'] ) ) $config['bot']['timestamp'] = "H:i:s";
	if( debug( false, 1 ) ) {
		intHeader( "Configuration \n", "=" );
		echo "Loading Primary and Secondary Databases\n";
	}
	$backupConfig = @unserialize( @file_get_contents( f( "config/backup.vsdf" ) ) );
	is_dir( f( "config/" ) ) || mkdir( f( "config/" ) );
	$d = opendir( f( "config/" ) );
	while( $file = readdir ( $d ) ) {
		if( substr( $file, strlen( $file ) - 4, 4) == "vudf" ) {
			$file = substr( $file, 0, strlen( $file ) - 5 );
			$config[$file] = eval( "return array(" . file_get_contents( f( "config/$file.vudf" ) ) . ");" );
			if ( $backupConfig[$file] != FALSE && $config[$file] !== FALSE ) {
				//If both configs load properly
				if( $backupConfig[$file] != $config[$file] ) {
					//If databases do not match.
					debug( "[" . date( "H:i:s" ) . "] $file (x)" );
					if ( FriendlyErrors && debug() ) echo " Primary and Secondary database files do not match.\n";
					save_config( $file );
				} else {
					debug( "[" . date( "H:i:s" ) . "] $file (ok)" );
				}
			} elseif( $backupConfig[$file] === FALSE && $config[$file] !== FALSE ) {
				// If only the primary loads
				debug( "[" . date( "H:i:s" ) . "] $file (!2)" );
				if ( FriendlyErrors && debug() ) echo " Secondary database is corrupt. Restoring from Primary.\n";
				sleep(1);
				$backupConfig[$file] = $config[$file];
				save_config( $file );
			} elseif( $backupConfig[$file] !== FALSE && $config[$file] === FALSE ) {
				// If only the backup loads properly
				debug( "[" . date( "H:i:s" ) . "] $file (!1)" );
				if ( FriendlyErrors && debug() ) echo " Primary database is corrupt. Restoring from Secondary.\n";
				sleep(1);
				$config[$file] = $backupConfig[$file];
				save_config( $file );
			} else {
				//If neither loads properly
				debug( "[" . date( "H:i:s" ) . "] $file (!1!2)" );
				if ( FriendlyErrors && debug() ) echo " Both primary and secondary database files ($file) are corrupt.\n";
				sleep(1);
			}
		}
	}
}

/**
* Store config to a vudf file
*@version 0.7
*@return void
*/
function save_config( $type = FALSE, $force = FALSE ) {
	global $config;
	if ( $config['bot']['thumbMode'] == FALSE || $force == TRUE ) {
		if( !save_bsv( f( "config/backup.vsdf" ), $config ) ) {
			intMsg( "Error saving backup" );
		}
	}
	xsave_config( $type, $force );
}
/**
*Saves the actual vudf files
*@return void
*/
function xsave_config( $type, $force ) {
	global $config;
	if ( $config['bot']['thumbMode'] == FALSE || $force == TRUE ) {
		if( !$type ) {
			foreach( $config as $file => $array ) {
				xsave_config( $file, $force );
			}
		} elseif ( $type == TRUE && is_array($config[$type]) == TRUE) {
			$file	=	fopen( f( "config/$type.vudf"), 'w' );
			$o		=	var_export( $config[$type], TRUE );
			$o		=	substr( $o, 8, strlen( $o ) - 9 );
			$o		=	substr( $o, 0, strlen( $o ) - 1 );
			$o		=	explode( "\n", $o );
			foreach( $o as $i => $l) $o[$i] = substr( $l, 2, strlen( $l ) - 2 );
			$o = implode( "\n", $o );
			fwrite( $file, $o );
			fclose( $file );
		}
	}
}


/**
*Checks stored configuration for proper settings
*
*Builds a copy if one is not found.
*@author SubjectX52873M <SubjectX52873M@gmail.com>
*@version 0.7
*@return void
*/
function checkConfig() {
	global $config, $debugLevel;
	$ini = parse_ini_file( f( "config.ini" ) );
	
	if ( $ini === FALSE ) {
		intmsg( 'FATAL: Unable to load config.ini' );
		die();
	}
	$die = FALSE;
	if ( empty( $ini['username'] ) ) {
		intmsg( 'FATAL: No username in config.ini' );
		$die = TRUE;
	}
	if ( empty( $ini['owner'] ) ) {
		intmsg( 'FATAL: No owner in config.ini' );
		$die = TRUE;
	}
	if ( empty( $ini['password'] ) ) {
		intmsg( 'FATAL: No password in config.ini' );
		$die = TRUE;
	}
	if ( empty( $ini['trigger'] ) ) {
		intmsg( 'FATAL: No trigger in config.ini' );
		$die = TRUE;
	}
	if ( $die ) die();
	
	if ( isset( $ini['FriendlyErrors'] ) ) define( 'FriendlyErrors', $ini['FriendlyErrors'] ? TRUE : FALSE );
	else define ( 'FriendlyErrors' , TRUE );
	if ( isset( $ini['EnhancedLogging'] ) ) define( 'EnhancedLogging', $ini['EnhancedLogging'] ? TRUE : FALSE );
	else define( 'EnhancedLogging', FALSE );
	
	global $debugLevel;
	$debugLevel = 4;
	if ( isset( $ini['DebugLevel'] ) ) {
		if( is_numeric( $ini['DebugLevel']) ){
			$ini['DebugLevel'] = floor( $ini['DebugLevel'] );
			if( $ini['DebugLevel'] < 5 ) $debugLevel = $ini['DebugLevel'];
			else $debugLevel = 5;
		} else $debugLevel = 5;
	}
	//Bot Unique ID
	/*$botID=load_bsv( f( "config\keys.vsdf" ) );
	if ( $botID === FALSE ) {
		$botID = $ini['username'] . " " . md5( uniqid( " ", TRUE ) );
		save_bsv( f( "config\keys.vsdf" ), $botID );
	}
	define( "BotID", $botID );//*/
	
	$botarray = array(
		'username'				=>	$ini['username'],
		'trigger'				=>	$ini['trigger'],
		'owner'					=>	$ini['owner'],
		'about' 				=> 	$ini['about'],
		'AutoRejoinWhenKicked' 	=> 	$ini['rejoin'] ? TRUE : FALSE,
		'timestamp' 			=> 	$ini['console'],
		'timestamp_log' 		=> 	$ini['logs'],
		'BadCommandMsg' 		=> 	$ini['Bad'],
		'RestrictedCommandMsg' 	=> 	$ini['Privs'],
		'thumbMode'				=>	$ini['ThumbDriveMode'] ? TRUE : FALSE,
		'logChats'				=>	$ini['Logging'] ? TRUE : FALSE,
		'HomeRoom'				=>	$ini['homeroom'],
		'EraseConfigNextRun'	=>	'no',
		'reconAttempts'			=>	intval( $ini['Attempts'] ),
		'showWhois'				=>	TRUE,
	);
	if ( !is_array( $config['bot'] ) ) $config['bot'] = array();
	$config['bot'] = array_merge( $config['bot'], $botarray );
	if ( $config['bot']['EraseConfigNextRun'] !== 'no' ) {
		$config['ignore']	=	array(); 
		$config['users']	=	array();
		$config['access']	=	array(
									'priv'		=>	array(),
									'default'	=>	array(
										'guests'	=>	0,
										'commands'	=>	0,
										'errors'	=>	0,
										'trigcheck'	=>0,
										'events'	=>0,
									),
									'flood' => array (
			  							'use' 		=>	TRUE,
										'period' 	=> 	30,
										'times' 	=> 	5,
										'timeout' 	=> 	60,
										'ruse' 		=> 	TRUE,
										'rperiod' 	=> 	3600,
										'rtimes'	=> 	5,
										'rtimeout' 	=> 	1800,
									),
								); //Clear out access
	}
	//Make sure that the home room is always joined.
	if ( !is_array( $config['bot']['rooms'] ) ) {
		$config['bot']['rooms'] = array();
	}
	$config['bot']['rooms'] = array_unique( array_merge( $config['bot']['rooms'], array( $ini['homeroom'] ) ) );
	define( "BotHomeRoom", $ini['homeroom'] );

	$error = FALSE;
	if ( $config['bot']['username'] !== $ini['username'] ) { 
		intmsg( 'Notice: Username in config.ini does not match setting in database. Fixing.');
		$config['bot']['username'] = $login['username'];
		//If there is a new user you need a new token
		$config['bot']['token'] = ''; 
		$error = TRUE;
	}
	if ( $config['bot']['owner'] !== $ini['owner'] ) { 
		$config['bot']['owner'] = $login['owner'];
		intmsg( 'Notice: Owner in config.ini does not match setting in database. Leaving Alone.' );
		$error = FALSE;
	}
	if ( $config['bot']['trigger'] !== $ini['trigger'] ) { 
		intmsg( 'Notice: Trigger in config.ini does not match setting in database.' );
		$config['bot']['trigger'] = $ini['trigger'];
		$error = FALSE;
	}
	if ( !is_array( $config['access']['priv'] ) ) {
		$config['access']['priv'] = array();
	}
	if ( !isset( $config['access']['default']['guests'] ) ) {
		$config['access']['default']['guests'] = 0;
		intmsg( 'Notice: Missing guest priv default... Setting to 0' );
		$error = TRUE;
	}
	if ( !isset( $config['access']['default']['errors'] ) ) {
		$config['access']['default']['errors'] = 0;
		intmsg( 'Notice: Missing error priv default... Setting to 0' );
		$error = TRUE;
	}
	if ( !isset($config['access']['default']['commands'] ) ) {
		$config['access']['default']['commands'] = 0;
		intmsg( 'Notice: Missing commands priv default... Setting to 0' );
		$error = TRUE;
	}
	if ( !isset( $config['access']['default']['trigcheck'] ) ) {
		$config['access']['default']['trigcheck'] = 0;
		intmsg( 'Notice: Missing trigcheck priv default... Setting to 0' );
		$error = TRUE;
	}
	if ( !isset( $config['access']['default']['events'] ) ) {
		$config['access']['default']['events'] = 0;
		intmsg( 'Notice: Missing events priv default... Setting to 0' );
		$error = TRUE;
	}
	array_walk( $config['access']['priv'], 'accessFixWalk' );
	array_walk( $config['access']['default'], 'accessFixWalk', TRUE);
	save_config( FALSE, TRUE ); //force save.
	//Generate a bot ID
	$x = @base64_decode( @file_get_contents( f( "config/bot.vsdf" ) ) );
	if( strlen($x) !== 32 ) {
		$x = base64_encode( md5( uniqid( "", TRUE ) ) );
		$f = fopen( "config/bot.vsdf", 'w' );
		fwrite( $f, $x );
		fclose( $f );
	}
	/**
	*BotID 
	*
	*This is not for tracking bots, this is for future use 
	*@constant string BotID
	*/
	define( "BotID", $x );
}

?>
