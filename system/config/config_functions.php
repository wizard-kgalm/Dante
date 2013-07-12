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


class config {
	
	//Need our bot variable, for use with /config/bot.df's contents. :D
	public $bot;
	//For the logins list. 
	public $logins;
	
	//For my commands, and updates, this is the function used for it. Eventually, I will remove this from there and solely use save_config. 
	function save_info( $file, $data, $backup = FALSE ) {
		$this->save_config( $file, $data, $backup );
	}
	//New method for saving the config below. 
	function save_config ( $file, $data, $backup = FALSE ) {
		//We are going to make a new backup system for the bot. There will be a command that allows you to restore, or update the backup. 
		is_dir( "./config/backup/" ) || mkdir( "./config/backup/" );
		file_put_contents( $file, "<?php\n\nreturn ".var_export( $data, true ).";\n\n?>" ); 
		if( $backup ){
			$file = explode( "./config/", $file );
			$file = "./config/backup/" . strtolower( $file[1] );
			//For normal saves, we won't back up. On quitting OR restart, you can choose to back up. Restoring from back up to be included.
			file_put_contents( $file, "<?php\n\nreturn ".var_export( $data, true ).";\n\n?>" );
		}
	}
	
	/**
	* Restore bot. This is basically a "factory reset" for the bot. What we're going to do is clear out the entire config folder, and then it will reset the config.ini too. 
	*@version 1.0
	*@returns void
	*/
	function restore_bot(){
		foreach( $this->df as $file => $contents ) {
			unlink( "./config/{$file}.df" );
			$this->df[$file] = NULL;
		}
		$this->build_config( FALSE );
	}
	
	/**
	* Construct config.ini. This is mostly for resetting the config.ini file, say if you changed it at all, or you're giving a copy to someone, or cloning the bot. 
	*@version 1.0
	*@returns the file to default. 
	*/
	function build_config( $build = FALSE, $username = "", $owner = "", $trigger = "", $homeroom = "" ){
		$reconfig = file_get_contents( "./system/functions/reconfig/config.ini" );
		//Let's put those into an array for something.
		$check = array( $username, $owner, $trigger, $homeroom );
		foreach( $check as $values ){
			//If we're compiling a new config on first launch, that will be set to TRUE. If we're cleaning out the bot, we'll want an empty config.ini. 
			if( $build ) {
				( !empty( $values ) ) ? $values = $values : $values = "";
			} else {
				$values = "";
			}
		}
		//Replacing the special strings with their values, so on load, it can compile that into the bot.df file..
		$reconfig = str_replace( "{username}", $username, $reconfig );
		$reconfig = str_replace( "{owner}"   , $owner   , $reconfig );
		$reconfig = str_replace( "{trigger}" , $trigger , $reconfig );
		$reconfig = str_replace( "{homeroom}", $homeroom, $reconfig );
		//Opening, or creating, the file and saving the information.. 
		$file     = fopen( f( "config.ini" ), "w" );
		fwrite( $file, $reconfig );
		//Done! You now have a config.ini file. Don't touch the base template it builds this from. 
		fclose( $file );
	}
	
	/**
	* Restore backup to config. Unlike the original config, this isn't done automatically on start up. You can restore if you mess up a file or something.
	*@version 1.0
	*@returns only on failure.
	*/
	function restore_backup( $file ) {
		//Checking for existence of back up.
		if( file_exists( "./config/backup/{$file}.df" ) ) {
			//Replacing original with back up, forecefully.
			$this->df[$file] = include "./config/backup/{$file}.df";
			$this->save_config( "./config/{$file}.df", $this->df[$file] );
		} else {
			return "No backup exists for {$file}.";
		}
	}
		
	/**
	* Load config from a df file
	*@version 1.2
	*@return void
	*/
	function load_config() {
		global $config, $seperator;
		if( !isset( $this->bot['timestamp'] ) ) $this->bot['timestamp'] = "H:i:s";
		is_dir( f( "config/" ) ) || mkdir( f( "config/" ) );
		$d = opendir( f( "config/" ) );
		while( $file = readdir ( $d ) ) {
			$file2 = substr( $file, 0, strlen( $file ) - 5 );
			if( substr( $file, strlen( $file ) - 4, 4) == "vudf" && !file_exists( "./config/{$file2}.df" ) ) {
				$file = substr( $file, 0, strlen( $file ) - 5 );
				$newfile = eval( "return array(" . file_get_contents( f( "config/$file.vudf" ) ) . ");" );
				$this->save_info( "./config/{$file}.df", $newfile );
				unlink( "./config/{$file}.vudf" );
			}
			if( substr( $file, strlen( $file ) - 3, 3) == ".df" ) {
				$file = substr( $file, 0, strlen( $file ) - 3 );
				$this->df[$file] = include "./config/{$file}.df";
				//Setting special variables alongside the $config->df locations, that way, we can use them for commands.
				if( $file == "bot" ){
					$this->bot = include "./config/{$file}.df";
				}
				if( $file == "logins" ){
					$this->logins = include "./config/{$file}.df";
				}
				if( $file == "notes" ){
					$this->notes = include "./config/{$file}.df";
				}
				if( $file == "llama" ) {
					$this->llama = include "./config/{$file}.df";
				}
			}
		}
	}

	/**
	*Checks stored configuration for proper settings
	*
	*Builds a copy if one is not found.
	*@author SubjectX52873M, heavy editting by Wizard-Kgalm
	*@version 1.2
	*@return void
	*/
	function checkConfig( $refresh ) {
		global $debugLevel;
		$ini = parse_ini_file( f( "config.ini" ) );
		if ( $ini === FALSE ) {
			intmsg( 'FATAL: Unable to load config.ini' );
			die();
		}
		$check = array( 'username', 'owner', 'trigger', );
		foreach( $check as $stuff ){
			if( empty( $ini[$stuff] ) ){
				intmsg( "FATAL: No {$stuff} in config.ini" );
				die( );
			}
		}
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
		
		$botarray = array(
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
			'EraseConfig'			=>	"no",
			'reconAttempts'			=>	intval( $ini['Attempts'] ),
			'showWhois'				=>	TRUE,
		);
		
		if( empty( $this->bot['username'] ) || $refresh == "yes" ) {
			$botarray['username'] = $ini['username'];
		}
		if( !is_array( $this->bot ) ){
			$this->bot = array();
		}
		$this->bot = array_merge( $this->bot, $botarray );
		if( $this->bot['EraseConfig'] !== "no" ){
			$ignoref = array();
			$usersf  = array();
			$accessf = array(
						'priv'		=>	array(),
						'default'	=>	array(
							'guests'	=>	0,
							'commands'	=>	0,
							'errors'	=>	0,
							'trigcheck'	=>  0,
							'events'	=>  0,
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
					);
			$this->save_info( "./config/ignore.df", $ignoref );
			$this->save_info( "./config/users.df" , $usersf  );
			$this->save_info( "./config/access.df", $accessf );
		}
		//Make sure the home room is always joined. 
		if( empty( $this->bot['rooms'] ) ){
			$this->bot['rooms'] = array ( $ini['homeroom'] , );
		}
		define( "BotHomeRoom", $ini['homeroom'] );
		$privs = array( 'guests', 'errors', 'commands', 'trigcheck', 'events', );
		foreach( $privs as $priv ){
			if( !isset( $this->df['access']['default'][$priv] ) ){
				$this->df['access']['default'][$priv] = 0;
				intmsg( "Notice: Missing {$priv} priv default... Setting to 0" );
			}
		}
		array_walk( $this->df['access']['priv'], 'accessFixWalk' );
		array_walk( $this->df['access']['default'], 'accessFixWalk', TRUE);
		$this->save_info( "./config/access.df" , $this->df['access'] );
		$this->save_info( "./config/bot.df" , $this->bot );
	}
}
?>
