<?php
/**
 * Modules Class
 *
 * This makes it possible to load modules.
 *
 *You CANNOT use xbot or tarbot modules in Dante.
 *
 * Reserved names that can not be used for modules: Bot, Access, Users, Priv, Ignored.
 *
 * This is to save the $config slots for builtin stuff.
 *
 *@author infinity0
 *@author SubjectX52873M <SubjectX52873M@gmail.com>
 *@version 0.8
 */

/**
 *Module Class
 *@author infinity0
 *@author SubjectX52873M <SubjectX52873M@gmail.com>
 *@version 0.8
 */
class Module {
  /**
	 * Name of the module
	 *@var string $name
	 */
	var $name;
	/**
	 * Array of registered events
	 *
	 * If an event is not registered it is null
	 *@var array $events
	 */
	var $events;
	/**
	 * Module Autoload
	 *
	 * True, module is loaded at startup: False, module must be loaded manually
	 *@var bool $status
	 */
	var $status;
	/**
	 * Module Status
	 *
	 * True, module is loaded : False, module is not loaded
	 *@var bool $loaded
	 */
	var $loaded;
	/**
	 * Core Module?
	 *
	 * True, module is core and must be loaded : False, module is not core
	 *
	 * If a module is a core modules it must have $status be true or 1.
	 *@var bool $iscore
	 */
	var $iscore;
	/**
	 * Module Author
	 *@var string $author
	 */
	var $author;
	/**
	 * Module Title
	 *@var string $title
	 */
	var $title;
	/**
	 * Module Version
	 *@var string $version
	 */
	var $version;
	/**
	 * Module About Messsage
	 *
	 * Default value is "{title} {version} by :dev{author}:."
	 *@var string $author
	 */
	var $about;
	/**
	 * Undocumented
	 *
	 *@doto Docuemnt this variable
	 *@var mixed $data
	 */
	var $data;
	/**
	 *Author, description of module
	 *
	 *@var array $metadata
	 */
	var $metadata;

	/**
	 *Stored whether or not the module has metadata
	 *
	 *Without metadata the module is not loaded
	 *@var bool $loadable
	 */
	var $loadable;

	/**
	 * This sets the module's name and autoload status
	 * This sets setup the basics
	 *@see Module::setInfo
	 *@return void
	 *@modlevel none
	 *@version 0.6
	 */
	function Module( $name, $status = 1 ) {
		$this->name 	= 	$name;
		$this->status 	= 	$status;
		$this->events	=	array(	'cmds'	=>	array()	);
		$this->loaded 	= 	FALSE;
		$this->iscore 	= 	FALSE;
		$this->loadable	=	TRUE;
	}
	/**
	 * Sets the module infomation
	 *
	 *This includes the metadata loader of Tarbot 0.3
	 *@version 0.6
	 *@return void
	 */
	function setInfo( $file ) {
		$f = file( $file );
		global $config;
		// METADATAS --------
		$detecting = FALSE;
		foreach( $f as $line ) {
			$line = rtrim( $line );
			if( $detecting ) {
				if( preg_match("/^[ \t]*\/\/ (.+): (.+)$/", $line, $ma ) ) {
					$meta = $ma[2];
					$meta = str_replace( "{tr}", $config['bot']['trigger'], $meta );
					$this->metadata[$ma[1]] = $meta;
				}
				if( preg_match("/^[ \t]*\/\/ \=+$/", $line ) ) {
					$detecting = FALSE;
					break;
				}
			} else {
				if( preg_match( "/^[ \t]*\/\/ DANTE MODULE \=+$/", $line ) ) {
					$detecting = TRUE;
				}
			}
		}

		if( !isset( $this->metadata['Name'] ) || !isset( $this->metadata['Description'] ) || !isset($this->metadata['Author'] ) ) {
			internalMessage( "Not all required metadatas are set for module \"" . $m . "\". Please make sure that the Name, Description and Author metadatas are set." );
			//Skip this module when loading
			$this->loadable = FALSE;
				
		}
		$this->author		=	$this->metadata['Author'];
		$this->title		=	$this->metadata['Name'];
		$this->version		=	$this->metadata['Version'];
		$this->about		=	$this->metadata['Description'];
	}

	/**
	 * Vardump of the class
	 *@return void
	 *@author infinity0
	 *@version 0.7
	 *@return void
	 */
	function debug() {
		if( !$this->loadable ) {
			print "Error, module is missing proper metadata";
			return;
		}
		print_r( $this );
	}

	/**
	 * Sets a Command
	 *
	 *$cmd is the command to register
	 *
	 *$file is the file that the commnad is in
	 *
	 *$priv is the default priv level, this is overridden by values in $config['access']
	 *
	 *$ds sets the on load command status, either on or off. (True:False, 1:0)
	 *@author infinity0
	 *@author SubjectX52873M <SubjectX52873M@gmail.com>
	 *@return void
	 *@version 0.4
	 */
	function addCmd( $cmd, $file, $priv = 'd', $ds = 1 ) {
		if( !$this->loadable ) return;
		global $config;
		if( $this->loaded ) {
			intmsg( 'Module "' . $this->name . '" has already been loaded. Changing its data will have no effect.', 'info' );
			return false;
		}
		if( !isset( $config['access']['priv'][$cmd] ) ) {
			$config['access']['priv'][$cmd] = $priv; // Set the priv
			save_config( 'access' );
		}
		$priv = $config['access']['priv'][$cmd]; //Override from global.
		if( !file_exists( f( 'modules/' . $this->name ) . '/' . $file . '.php' ) ) {
			intmsg( 'File "' . f('modules/' . $this->name) . '/' . $file . '.php" does not exist. Cancelling command.' );
		} else {
			$this->events['cmds'][$cmd] = array(
				'file'	=>	$file,
				'priv'	=>	$priv,
				'status'=>	$ds
			);
		}
	}

	/**
	 *Add command help
	 *@return mixed False on errors, void on success
	 */
	function addHelp( $cmd, $help = ':confused: Help is added but not set.' ) {
		if( !$this->loadable ) return;
		if( $this->loaded ) {
			intmsg( 'Module "' . $this->name . '" has already been loaded. Changing its data will have no effect.', 'info' );
			return false;
		}
		if( !isset( $this->events['cmds'][$cmd] ) ) {
			intmsg( 'Command "' . $cmd . '" does not exist, skipping help. Be sure that you set the command before the help.' );
			return false;
		}
		$this->events['cmds'][$cmd]['help'] = $help;
	}
	/**
	 * Sets an Event
	 *
	 *$trigger is the event to register, see the source for a list of events.
	 *
	 *$file is the file that is called (using include) to handle the event
	 *
	 *$priv is the default priv level, this is overridden by values in $config['access']
	 *
	 *$ds is the on load event status, on or off (True:False, 1:0)
	 *@author infinity0
	 *@author SubjectX52873M <SubjectX52873M@gmail.com>
	 *@version 0.4
	 */
	function addEvt( $trigger, $file, $priv = 'd', $ds = 1 ) {
		if(!$this->loadable) return;
		global $config;
		if( $trigger == 'cmds' ) {
			intmsg( 'Use setCmd() instead.', 'info' );
			return false;
		}
		if( $this->loaded ) {
			intmsg( 'Module "' . $this->name . '" has already been loaded. Changing its data will have no effect.', 'info' );
			return false;
		}
		if( !isset( $this->events[$trigger] ) ) {
			$this->events[$trigger] = array();
		}
		if( !file_exists( f( 'modules/' . $this->name ) . '/' . $file . '.php' ) ) {
			intmsg( 'File "' . f( 'modules/' . $this->name ) . '/' . $file . '.php" does not exist. Cancelling event.' );
		} //<ClanCC> SubjectX52873M: i don't get to go in source? :(]
		else {
			$this->events[$trigger][$file] = array(
				'file'	=>	$file,
				'priv'	=>	$priv,
				'status'=>	$ds,
				'hook'	=>	FALSE,
			);
		}
	}

	/**
	 * Sets an Event
	 *
	 *grabs all messages, without parsing out the bot or ignored users
	 *
	 *$trigger is the event to register, see the source for a list of events.
	 *
	 *$file is the file that is called (using include) to handle the event
	 *
	 *$priv is the default priv level, this is overridden by values in $config['access']
	 *
	 *$ds is the on load event status, on or off (True:False, 1:0)
	 *@author infinity0
	 *@author SubjectX52873M <SubjectX52873M@gmail.com>
	 *@version 0.4
	 */
	function addHook($trigger,$file,$priv='d',$ds=1) {
		if(!$this->loadable) {return;}
		global $config;
		if($trigger=='cmds') {
			intmsg('Use setCmd() instead.','info');return false;
		}
		if($this->loaded) {
			intmsg('Module "'.$this->name.'" has already been loaded. Changing its data will have no effect.','info');return false;
		}
		if(!isset($this->events[$trigger])) {
			$this->events[$trigger]=array();
		}
		if(!file_exists(f('modules/'.$this->name).'/'.$file.'.php')) {
			intmsg('File "'.f('modules/'.$this->name).'/'.$file.'.php" does not exist. Cancelling event.');
		}
		else {
				
			$this->events[$trigger][$file] = array('file'=>$file,'priv'=>$priv,'status'=>$ds,'hook'=>true);
		}
	}

	/**
	 *Load Module
	 *
	 * This registers the module's commands and events into the global varaible $events
	 *@return mixed False on errors, String on success
	 *@version 0.6
	 */
	function load( $file ) {
		if( !$this->loadable ) return false;
		global $events;
		if( $this->loaded ) {
			intmsg( 'This module ("' . $this->name . '") has already been loaded.', 'error' );
			return false;
		}
		foreach( $this->events as $type => $typedata ) {
			if ( !isset( $events[$type] ) ) {
				intmsg( 'Module "' . $this->name . '" contains data for unknown eventtype "' . $type . '". Ignoring data and REMOVING it from module.', 'warning' );
				unset( $this->events[$type] );
				break;
			}
			if ( $type == 'cmds' ) {
				foreach( $typedata as $index => $data ) {
					if( isset( $events[$type][$index] ) ) {
						intmsg( 'Module "' . $this->name . '" is trying to register a command "' . $index . '" which is already in use (' . $events[$type][$index]['file'] . '.php). Ignoring new command and REMOVING it from conflicting module.', 'warning' );
						unset( $this->events[$type][$index] );
					} else {
						$filename = $this->name . '/' . $data['file'] . '.php';
						$stat = ( $this->status ) ? ( $data['status'] ) : ( 0 );

						$events[$type][$index] = array(
							'file' 		=> 	$filename,
							'priv' 		=> 	$data['priv'],
							'status'	=>	$stat,
							'module' 	=>	$this->name,
							'help' 		=>	$data['help'],
						);
					}
				}
			} else {
				foreach( $typedata as $index => $data ) {
					$filename = $this->name . '/' . $data['file'] . '.php';
					$stat = ( $this->status ) ? ( $data['status'] ): ( 0 );
						
					$events[$type][$this->name . '/' . $index] = array(
						'file' 		=> 	$filename,
						'priv' 		=> 	$data['priv'],
						'status' 	=> 	$stat,
						'module' 	=> 	$this->name,
						'hook' 		=> 	$data['hook'],
					);
				}
			}
		}
		$status = ( $this->status ) ? ( 'activated' ) : ( 'deactivated' );
		$return = ( $this->status ) ? ( $file ) : ( "$file*" );
		$this->loaded = TRUE;
		if( debug( false, 3 ) ) intmsg( 'Module "' . $this->name . '" has been loaded. It is currently ' . $status . '.', 'info' );
		return $return;
	}

	/**
	 *Set Command Status
	 *
	 *$cmd is the command and $status is the status (True:False, 1:0) to set
	 *
	 *@return bool True on success, False on failure
	 *@version 0.6
	 */
	function setCmdStatus( $cmd, $status ) {
		if( !$this->loadable ) return;
		$status = ($status) ? ( TRUE ) : ( FALSE );
		if ( !$this->loaded ) {
			intmsg( 'Module "' . $this->name . '" has not been loaded yet. Load it before activating/deactivating.', 'info' );
			return false;
		}
		global $events;
		$status = ( $s ) ? ( 'on' ) : ( 'off' );
		if( !isset( $events['cmds'][$cmd] ) ) {
			intmsg( 'Command "' . $cmd . '" does not exist.', 'error' );
			return false;
		} elseif( $events['cmds'][$cmd] != $this->name ) {
			intmsg( 'Command "' . $cmd . '" does not belong to this module and so cannot be changed.', 'error' );
			return false;
		} else {
			intmsg( 'Command "' . $this->name . ':' . $cmd . '" switched ' . $status . '.', 'info' );
			$events['cmds'][$cmd]['status'] = $s;
			return true;
		}
	}


	/**
	 *Set Command Status
	 *
	 *$event is the command and $status is the status (True:False, 1:0) to set
	 *
	 *$file is the file that holds the code to handle events
	 *
	 *@return bool True on success, False on failure
	 *@author infinity0
	 *@author SubjectX52873M <SubjectX52873M@gmail.com>
	 *@version 0.2
	 */
	function setEvtStatus( $evt, $file, $status ) {
		if( !$this->loadable ) return;
		$status = ( $status ) ? ( TRUE ) : ( FALSE );
		if ( !$this->loaded ) {
			intmsg('Module "' . $this->name . '" has not been loaded yet. Load it before activating/deactivating.', 'info');
			return false;
		}
		global $event;
		$status = ( $status ) ? ( 'on' ) : ( 'off' );
		if( !isset( $event[$evt] ) || !isset( $event[$evt][$this->name . '/' . $file] ) ) {
			intmsg( 'Event "' . $evt . ':' . $file. '" does not exist or is not part of this module.', 'error' );
			return false;
		} else {
			intmsg( 'Event "' . $this->name . ':' . $evt . ':' . $file . '" switched ' . $status . '.', 'info' );
			$event[$evt][$this->name . '/' . $file]['status'] = $status;
			return true;
		}
	}

	/**
	 *Set Module Status
	 *
	 *$s is the status (True:False, 1:0) to set
	 *
	 *@return mixed False if module not loaded, does not return otherwise
	 *@author infinity0
	 *@author SubjectX52873M <SubjectX52873M@gmail.com>
	 *@version 0.1
	 */
	function setStatus($s) {
		if( !$this->loadable ) return;
		$s = ( $s )? ( TRUE ) : ( FALSE );
		if (!$this->loaded) {
		intmsg('Module "'.$this->name.'" has not been loaded yet. Load it before activating/deactivating.','info');return false;}
		if ($this->iscore && !$s ) intmsg( 'Cannot unload core module "' . $this->name . '".', 'error' );
		global $events;
		$status = ($s) ? ( 'activated' ) : ('deactivated' );
		if( $this->status == $s ) {
			intmsg( 'Module "' . $this->name . '" is already ' . $status . '.', 'info' );
		} else {
			$this->status = $s;
			foreach( $this->events as $type => $typedata ) {
				if ( $type == 'cmds' ) {
					foreach( $typedata as $index => $data ) {
						$stat = ( $s ) ? ( $data['status'] ) : ( 0 );
						$events[$type][$index]['status'] = $stat;
					}
				} else {
					foreach( $typedata as $index => $data ) {
						$stat = ( $s )? ( $data['status'] ) : ( 0 );
						$events[$type][$this->name . '/' . $index]['status'] = $stat;
					}
				}
			}
			intmsg('Module "' . $this->name . '" has been '.$status.'.', 'info' );
		}
	}

	//STOLEN FROM TARBOT 0.3
	/**
	 *Turns module on
	 *@return mixed False if module not loaded, does not return otherwise
	 */
	function activate() {
		return $this->setStatus( 1 );
	}

	//STOLEN FROM TARBOT 0.3
	/**
	 *Turns module off
	 *@return mixed False if module not loaded, does not return otherwise
	 */
	function deactivate(){
		return $this->setStatus( 0 );
	}
}

?>
