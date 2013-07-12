<?php
/**
 *Dante Core File
 *
 * Modules Loader
 *
 * This file loads modules from the folder "modules"
 *
 *You CANNOT use xbot modules in Dante.
 *@author SubjectX52873M <SubjectX52873M@gmail.com>
 *@copyright SubjectX52873M 2006
 *@package Dante
 *@license http://www.opensource.org/licenses/gpl-license.php GNU General Public License
 *@version 0.8
 */

/**
 * Set Events here.
 *
 * The events from loaded modules is stored here, on a reload the variable is erased so modules can register events
 *
 * Moved from modules.php
 *
 * @global array $events
 */
global $events;
$events = NULL;
$events = array(
	'loop' => array(),
	'cmds' => array(),
	'cmd-fail' =>array(),
	'run' => array(),
	'authtoken' => array(),
		'authtoken-fail'=>array(),
	'handshake' => array(),
	'login' => array(),
		'login-success'=> array(),
	'join' => array(),
		'join-success'=>array(),
		'join-fail'=>array(),
	'part' => array(),
		'part-success'=>array(),
		'part-fail'=>array(),
	'kicked' => array(),
	'property' => array(),
		'property-login-info' => array(),
		'property-privclasses' => array(),
		'property-members' => array(),
		'property-topic' => array(),
		'property-title' => array(),
	'send'=>array(),
	'get' => array(),
	'recv' => array(),
		'recv-msg' => array(),
		'recv-action' => array(),
		'recv-join' => array(),
		'recv-kicked' => array(),
		'recv-part' => array(),
		'recv-privchg' => array(),
		'recv-admin' => array(),
			'recv-admin-create'=>array(),
			'recv-admin-update'=>array(),
			'recv-admin-rename'=>array(),
			'recv-admin-remove'=>array(),
			'recv-admin-show-users' => array(),
			'recv-admin-show-privclass' => array(),
	'ping' => array(),
	'shutdown' => array(),
	'disconnect' => array(),
	'pchatParse'=>array(),
	'whois'=>array(),
);

$loadList = array();
global $modules;
$modules = NULL;
$modules = array();
$hd = f( "modules/" );
$modfolder = scandir( f( "modules" ), 1 );

// Load core modules first
$modules_test = $modules;
debug( '** Searching for core modules...', 1 );
foreach( $modfolder as $m ) {
	if( $m != '..' && $m != '.' && is_dir( $hd . $m ) && file_exists( $hd . $m . '/sysconfig.php' ) ) {
		if( isset( $modules_test[$m] ) ) {
			unset( $modules_test[$m] );
		}
		if( debug( false, 3 ) ) intmsg( 'Found core module "' . $m . '". loading...', 'info' );
		/**
		 *Load core module
		 */
		include( $hd . $m . '/sysconfig.php' );
		$modules[$m]->iscore = 1;
		$loadList[] = $modules[$m]->load( basename( $m, ".php" ) );
	}
}
if( !empty( $modules_test ) ) {
	intmsg('SYSTEM MODULES ' . implode(', ', $modules_test ) . ' were not loaded. Bot may not work correctly.', 'warning' );
}
sort( $loadList );
debug( "Loaded: " . implode( ", ", $loadList ), 1, 2);
$loadList = array();

// Then other modules
debug( '** Searching for extension modules...', 1 );
foreach( $modfolder as $m ) {
	if( $m != '..' && $m != '.' && is_dir( $hd.$m ) && file_exists( $hd . $m . '/config.php' ) && !isset( $modules[$m] ) ) {
		if( debug( false, 3 ) ) intmsg( 'Found module "' . $m . '". Loading...', 'info' );
		/**
		 * Load standard module
		 */
		include( $hd . $m . '/config.php' );
		$loadList[] = $modules[$m]->load( basename( $m, ".php" ) );
	}
}
sort( $loadList );
debug( "Loaded: " . implode( ", ", $loadList ), 1, 2 );
unset( $loadList );

?>
