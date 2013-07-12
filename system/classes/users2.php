<?php
/**
 * Users class
 *
 *@author SubjectX52873M <SubjectX52873M@gmail.com>
 *@copyright SubjectX52873M 2006
 *@package Dante
 *@license http://www.opensource.org/licenses/gpl-license.php GNU General Public License
 *@version 0.9
 */
/**
 *Users Class
 *
 *All Functions Recoded in 0.9
 *@author SubjectX52873M <SubjectX52873M@gmail.com>
 */

// TODO: Add guest level class stuff.

class users {

	public $ignoreList;

	function users() { // Initialization command.
		global $config;
		$this->standardUserArray = array(			// NOTE! This is only how the privclasses are set when the bot first launches.
			'users' => array(),	// REFER TO THE BOT MANUAL on how to rename and add/remove privclasses! (Because if you're editting this, you'll want to.)
			'groups' => array( 
				99 => array(
					"name" 	=>	"Owners",
					'priv'	=>	99,
					'time'	=>	0),
				75 => array(
					"name" => 	"Admins",
					'priv' =>	75,
					'time' =>	0),
				50 => array(
					"name" => 	"Operators",
					'priv' =>	50,
					'time' =>	0),
				25 => array(
					"name" => 	"Members",
					'priv' =>	25,
					'time' =>	0),
				0 => array(
					"name" => 	"Guests",
					'priv' =>	0,
					'time' =>	0),
				-1 => array(
					"name" => 	"Banned",
					'priv' =>	-1,
					'time' =>	0),
			),
		);
		if( !is_array( $config->df['users'] ) ) $config->df['users'] = array();
		if ( !array_key_exists( 'groups', $config->df['users'] ) || !array_key_exists( 'users', $config->df['users'] ) ) {
			$config->df['users'] = $this->standardUserArray;
			$config->save_info( "./config/users.df", $config->df['users'] ); 
		}
		$this->ignoreList = array();
	}

	/**
	 * Adds a user to a priv class.
	 *
	 * Accepts the level (99) of the class not the name (Owners)
	 *@return bool True on success, False on failure
	 */
	function add( $username, $id ) {
		global $config;
		$user = strtolower( $username );
		if( !is_numeric( $id ) ) $id = $this->getClassByName( $id );
		if( !array_key_exists( $id, $config->df['users']['groups'] ) ) return FALSE;
		$data = array(
			'name'	=>	$username,
			'time'	=>	time(),
			'priv'	=>	$id);
		$config->df['users']['users'][$user] = $data;
		ksort( $config->df['users']['users'] );
		$config->save_info( "./config/users.df", $config->df['users'] ); 
		return true;
	}

	/**
	 *
	 *@return mixed array on success, false on failure
	 */
	function getInfo( $username, $id ) {
		global $config;
		$user = strtolower( $username );
		if( !array_key_exists( $user, $config->df['users']['users'] ) ) return false;
		else return $config->df['users']['users'][$user];
	}
	/**
	 *Remove a user
	 *@return bool True on success, False on failure
	 */
	function remove( $username ) {
		global $config;
		$user = strtolower( $username );
		if( !array_key_exists( $user, $config->df['users']['users'] ) ) return false;
		unset( $config->df['users']['users'][$user] );
		ksort( $config->df['users']['users'] );
		$config->save_info( "./config/users.df", $config->df['users'] ); 
		return true;
	}

	/**
	 * Does user ($username) has have the level ($privclass).
	 *
	 * Example, is the user an owner? user::has('SubjectX52873M',99)
	 *
	 * Example, is the user banned? !user::has('SubjectX52873M',0)
	 *@return bool
	 *@version 0.5
	 */
	//For the reason that you can put a ! in front of the function to invert the value I decided to only have one function (Like Tarbot and Noodlebot) instead of two functions (xbot)
	function has( $username, $privclass, $event = FALSE ) {
		global $config;
		$user = strtolower( $username );
		if( $user == strtolower( $config->bot['owner'] ) ) return TRUE;
		if ( !is_numeric( $privclass ) ) {
			if( $event ) $privclass = $config->df['access']['default']['events'];
			else $privclass = $config->df['access']['default']['commands'];
		}
			
		if( array_key_exists( $user, $config->df['users']['users'] ) ) $lowestpriv = $config->df['users']['users'][$user]['priv'];
		else $lowestpriv = $config->df['access']['default']['guests'];
		if( $lowestpriv >= $privclass ) return true;
		else return false;
	}

	/**
	 *Get User's priv level
	 *
	 *@author SubjectX52873M
	 *@version Dante 0.1
	 *@return integer
	 */
	function getPriv( $username ) {
		global $config;
		$user = strtolower( $username );
		if( array_key_exists( $user, $config->df['users']['users'] ) ) return $config->df['users']['users'][$user]['priv'];
		else return $config->df['access']['default']['guests'];
	}

	/**
	 * Get the PrivClass level (99) from the name (Owners)
	 *@return mixed String if name is a valid class, False if not
	 */
	function getClassIdFromName( $classname ) {
		global $config;
		foreach( $config->df['users']['groups'] as $i => $data ) {
			if( $data['name'] == $classname ) return $i;
		}
		return false;
	}

	/**
	 * Get the PrivClass name (Owners) from the level (99)
	 *@return mixed String if level is a valid class, False if not
	 */
	function getClassNameFromId( $classid ) {
		global $config;
		if( array_key_exists( $classid, $config->df['users']['groups'] ) && is_numeric( $classid ) ) {
			return $config->df['users']['groups'][$classid]['name'];
		} else return false;
	}

	/**
	 * Checks if the class is a defualt level. (99,-1)
	 *@return bool True if not, False if is.
	 */
	function isResPrivclass( $classid ) { // Is this a reserved privclass?
		if( $classid != 99 &&  $classid != -1 ) {
			return false;
		} else {
			return true;
		}
	}

	/**
	 * Create new privclass
	 *@return bool True on success, False on failure
	 */
	function addPrivclass( $classid, $classname ) {
		global $config;
		if( !array_key_exists( $classid, $config->df['users']['groups'] ) && $this->getClassIdFromName( $classname ) === FALSE && is_numeric( $classid ) ) {
			$data = array(
				'name'	=>	$classname,
				'time'	=>	time(),
				'priv'	=>	$classid
			);
			$config->df['users']['groups'][$classid] = $data;
			ksort( $config->df['users']['groups'] );
			$config->save_info( "./config/users.df", $config->df['users'] ); 
			return true;
		} else return false;
	}
	/**
	 * Remove a privclass and all users in that class.
	 *@return mixed # of users affected on success, False on failure
	 */
	function renamePrivclass( $classid, $newname ) { // Removes privclass AND everyone in it.
		if( !is_numeric( $classid ) ) $classid = $this->getClassIdFromName( $classid );
		global $config;
		if( array_key_exists( $classid, $config->df['users']['groups'] ) && is_numeric( $classid ) ) {
			$config->df['users']['groups'][$classid]['name'] = $newname;
			$config->save_info( "./config/users.df", $config->df['users'] ); 
			return TRUE;
		}else return FALSE;
	}

	/**
	 * Remove a privclass and all users in that class.
	 *@return mixed # of users affected on success, False on failure
	 *@todo Maybe return the users removed?
	 */
	function removePrivclass( $classid ) { // Removes privclass AND everyone in it.
		global $config;
		if( array_key_exists( $classid, $config->df['users']['groups'] ) && is_numeric( $classid )&& !$this->isResPrivclass( $classid ) ) {
			unset( $config->df['users']['groups'][$classid] );
			ksort( $config->df['users']['groups'] );
			$i = 0;
			foreach( $config->df['users']['users'] as $name => $data ) {
				if ( $data['priv']==$classid ) {
					unset( $config->df['users']['users'][$name] );
					$i++;
				}
			}
			return $i;
			$config->save_info( "./config/users.df", $config->df['users'] ); 
		}else return FALSE;
	}

	/**
	 *Returns a list of users
	 *@return array (## => user lsit, ## => userlist, etc.)
	 */
	function getUserList() {
		global $config;
		$return = array();
		foreach( $config->df['users']['groups'] as $i => $group ) {
			$return[$i] = array();
		}
		foreach( $config->df['users']['users'] as $i => $user ) {
			$return[$user['priv']][] = $user['name'];
		}
		return $return;
	}

	/**
	 *Check if a user is ignored
	 *@return bool
	 */
	function isIgnored( $user ) {
		$u = $user;
		$user = strtolower( $user );
		global $config;
		if( $user == strtolower( $config->bot['owner'] ) ) return false;
		else return array_key_exists( $user, $this->ignoreList );
	}
	/**
	 *Add a user to the ignore list
	 *@return bool
	 */
	function ignore( $user ) {
		$u = $user;
		$user = strtolower( $user );
		global $config;
		if( strtolower( $user ) == strtolower( $config->bot['owner'] ) ) return false;
		if( $this->isIgnored( $user ) ) return FALSE;
		else{ 
			$this->ignoreList[$user] = $u;
			sort( $this->ignoreList );
			return TRUE;
		}
	}
	/**
	 *Make use of the Dante Timer to ignore for a set time
	 *
	 *$time is an integer of the number of seconds to wait.
	 *@return bool
	 */
	function timedIgnore( $user, $time ) {
		global $Timer;
		if( $this->isIgnored( $user ) ) return FALSE;
		$code = 'global $user; $user->unignore( $data );';
		$this->ignore( $user );
		return $Timer->register( $time, $user, FALSE, $code );
	}
	/**
	 *Remove user from the ignore lsit
	 *@return bool
	 */
	function unignore( $user ) {
		$u = $user;
		if( !$this->isIgnored( $user ) ) {
			$i = array_search( strtolower( $user ), $this->ignoreList );
			unset( $this->ignoreList[$i] );
			ksort( $this->ignoreList );
			return TRUE;
		} else return FALSE;
	}
}
