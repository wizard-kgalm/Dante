<?php
/**
 * Room class for Dante
 *@author SubjectX52873M
 */
class Room {
  /**
	 * Name of the room
	 *@var string $name
	 */
	private $name;
	/**
	 * Title of the room
	 *
	 * array with keys: value,by,ts
	 *@var array $title
	 */
	private $title;
	/**
	 * Topic of the room
	 *
	 * array with keys: value,by,ts
	 *@var array $topic
	 */
	private $topic;
	/**
	 *Privclass for room
	 *
	 *##=>privclass, privclass=>##
	 *@var array $pcs
	 */
	private $pcs;
	/**
	 * Members for room
	 *
	 * 'name'	=> 	array (
	 * 		'name'			=> 	'SubjectX52873M',
	 * 		'usericon' 		=> 	'3',
	 * 		'symbol' 		=> 	'=',
	 * 		'symbolname' 	=> 	'Official Beta Tester',
	 * 		'realname' 		=> 	'Last Stop for Sanity',
	 * 		'typename' 		=> 	'Science Fiction Writer',
	 * 		'pc' 			=>	'The-Bot-Team',
	 * 		'gpc'			=>	'guest',
	 * );
	 * See source notes for structure
	 * @var array $members
	 */
	private $members;
	/**
	 *Hierarchy for room
	 *
	 * 	Array(
	 * 		pc	=>	array(
	 *			user1
	 *			user2
	 *		),
	 *	);
	 * See source notes for structure
	 * @var array $hierarchy
	 */
	private $hierarchy;

	/**
	 *Function to initalize all the arrays
	 *@return void
	 */
	function Room( $name ) {
		$this->name = $name;
		$this->title = $this->topic = array(
			'by'	=>	'', 
			'ts' 	=> 	'', 
			'value' => 	'',
		);
		$this->members = array();
		$this->pcs = array();
		$this->hierarchy = array();
	}

	/**
	 *Return a list of users in the room.
	 *@return array
	 */
	function getUserList() {
		return array_keys( $this->members );
	}
	/**
	 *Return data on a user in the room
	 *
	 *$type is the array key of any data you need.
	 *
	 *Possible Keys: name, usericon, symbol, symbolname, realname, typename, pc, gpc
	 *@return mixed Array if $type=false, String if $type= array key, False on error
	 */
	function getUserData( $user, $type = FALSE) {
		if( !array_key_exists( $user, $this->members ) ) return FALSE;
		if( $type === FALSE ) return $this->members[$user];
		else {
			if( array_key_exists( $type, $this->members[$user] ) ) {
				return $this->members[$user][$type];
			} else {
				return FALSE;
			}
		}
	}
	/**
	 *Return the privclasses in a room.
	 *@return array array(pc=>##,##=>pc)
	 */
	function getPrivclasses() {
		return $this->pcs;
	}
	/**
	 *Return User list as array("Privclass"=>:Users in class")
	 *
	 *If $number is true the privclass's order will be used instead of it's name.
	 *@return array array("Privclass"=>:Users in class")
	 */
	function getHierarchy( $number = FALSE) {
		//Use Privclass names as keys
		if( !$number ) return $this->hierarchy;
		//Use Privclass values as keys
		$list = array();
		foreach( $this->hierarchy as $pc => $user ) {
			$ipc = $this->pcs[$pc];
			$list[$ipc] = $user;
		}
		krsort( $list );
		return $list;
	}


	/**
	 *Return topic for the room
	 *@return array array('by'=>string,'ts'=>string,'value'=>string)
	 */
	function getTopic() {
		return $this->topic;
	}

	/**
	 *Return title for the room
	 *@return array array('by'=>string,'ts'=>string,'value'=>string)
	 */
	function getTitle() {
		return $this->title;
	}

	##################################
	#Data management
	##
	/**
	 * This section holds functions for handling data management.
	 */

	/**
	 *set the title for the room
	 *@return void
	 */
	function setTitle( $value, $by, $ts ) {
		$this->title = array(
			'by'	=>	$by,
			'ts'	=>	$ts,
			'value'	=>	$value,
		);
	}
	/**
	 *set the topic for the room
	 *@return void
	 */
	function setTopic( $value, $by, $ts ) {
		$this->topic = array(
			'by'	=>	$by,
			'ts'	=>	$ts,
			'value'	=>	$value,
		);
	}
	/**
	 *Set the privclasses
	 *@return void
	 */
	function setPcs( $data ) {
		foreach( $data as $i => $o ) {
			$this->pcs[$i] = $o;
			$this->pcs[$o] = $i;
		}
	}

	/**
	 *Load the members for the room into the class.
	 *@return void
	 */
	function userMembers( $members ) {
		global $dAmn;
		foreach( $members as $name => $data ) {
			if( !is_array( $this->members[$name] ) ) $this->members[$name] = $data;
			else $this->members[$name] = array_merge( $this->members[$name], $data );
			$this->members[$name]['count']++;
			$this->members[$name]['symbolname'] = $dAmn->symproc( $data['symbol'] );
			$this->hierarchy[$data['pc']][$name]++;
		}
	}
	/**
	 *Move users from one privclass to another
	 *
	 *THIS ONLY CHANGES THE BOTS DATA
	 *@return void
	 */
	function moveUsers( $prev, $pc ) {
		foreach( $this->members as $user => $data ) {
			if( $data["pc"] == $prev ) {
				$this->members[$user]["pc"] = $pc;
			}
		}
		if ( isset( $this->hierarchy[$pc] ) ) $this->hierarchy[$pc] = array_merge( $this->hierarchy[$pc], $this->hierarchy[$prev] );
		else $this->hierarchy[$pc] = $this->hierarchy[$prev];
		unset($this->hierarchy[$prev]);
	}

	/**
	 *Handle a renamed privclass.
	 *@return void
	 */
	function privRename( $prev, $pc ) {
		//Hierarchy
		$this->hierarchy[$pc] = $this->hierarchy[$prev];
		$users = array_keys( $this->hierarchy[$prev] );
		unset( $this->hierarchy[$prev] );
		//Members
		foreach( $users as $name ) {
			$this->members[$name]['pc'] = $pc;
		}
		//PCs
		$i = $this->pcs[$prev];
		$this->pcs[$i] = $pc;
		$this->pcs[$pc] = $i;
		unset( $this->pcs[$prev] );
	}
	/**
	 *Add user to room list.
	 *@return Integer Number of connections user has
	 */
	function userJoined( $user, $data ) {
		global $dAmn;
		if( !is_array( $this->members[$user] ) ) $this->members[$user] = $data;
		else $this->members[$user] = array_merge( $this->members[$user], $data );
		$this->members[$user]['count']++;
		$this->members[$user]['symbolname'] = $dAmn->symproc( $data['symbol'] );
		$this->hierarchy[$data['pc']][$user]++;
		return $this->members[$user]['count'];
	}
	/**
	 *Handle a privchange in the room
	 *return void
	 */
	function userPrivChange( $user, $pc ) {
		$prev = $this->members[$user]['pc'];
		//Clear hierarchy
		$this->hierarchy[$pc][$user] = $this->hierarchy[$prev][$user];
		unset( $this->hierarchy[$prev][$user] );
		$this->members[$user]['pc'] = $pc;
	}
	/**
	 *Remove user from room list.
	 *@return Integer Number of connections user has
	 */
	function userParted( $user ) {
		$pc = $this->members[$user]['pc'];
		if( $this->hierarchy[$pc][$user] == 1 ) {
			unset( $this->members[$user],$this->hierarchy[$pc][$user] );
			return 0;
		} else {
			$this->members[$user]['count'] = $this->members[$user]['count'] - 1;
			$this->hierarchy[$pc][$user] = $this->hierarchy[$pc][$user] - 1;
			return $this->members[$user]['count'];
		}

	}
	/**
	 *Removes a privclass from the hierarchy
	 *@return void
	 */
	function removePC( $pc ) {
		unset( $this->hierarchy[$pc] );
	}
	/**
	 *Delete user data from the room list
	 *@return void
	 */
	function userKicked( $user ) {
		$pc = $this->members[$user]['pc'];
		unset( $this->members[$user], $this->hierarchy[$pc][$user] );
	}
}
