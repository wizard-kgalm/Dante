<?php
/**
 * Timer class
 *
 *@author SubjectX52873M <SubjectX52873M@gmail.com>
 *@copyright SubjectX52873M 2006
 *@origin Dante 0.4
 *@modlevel Meduim
 *@package Dante
 *@license http://www.opensource.org/licenses/gpl-license.php GNU General Public License
 *@version 0.4
 */
/**
 *Timer Class
 *
 *Allows timed events to fire later.
 *
 *There is no accessable varibles in this class, all variables are internal to the class. This is by design.
 *
 *Functions are used to access register and fire events.
 *
 *This module is self-contained.
 *@orign Dante 0.4
 *@author SubjectX52873M <SubjectX52873M@gmail.com>
 *@version 0.4
 */
class Timer {
  /**
		*Holds a list of events for the timer
		*
		*Array ( ##=> Array(time=>time(), (file=>file)|(code=>code)|cached=>cachedfile)
		*@var array $events
		*/
	private $events;

	/**
		*Stores whether or not the class it initiated.
		*@var bool $init
		*/
	private $init;


	/**
		*Sets up the array $this->events
		*
		*@return bool True if the function has been initiated, False if it has been initiated before.
		*/
	function init() {
		if ( !$this->init ) {
			$this->init = TRUE;
			$this->events = array();
			return true;
		} else {
			return false;
		}
	}

	/**
		*Register an event to occur in the future
		*
		*For recurring events use Timer::registerRecurring()
		*
		*There are two ways of having things happen when a time event is fired.
		*
		*1. Include a file.
		*
		*2. Eval() custom code (Dangerous) Only used if $filename=false
		*
		*Timed events do not have access to  the normal range of data a module would. You need to store the data a timed event will use.
		*
		*$time the number of seconds in which to fire the event. Must be 1 or greater or it's not registered. Floor() is called on $time so don't use decimals.
		*
		*$data is storage for infomation that a timed event needs. The timer class is not able to call commands normally, although you could create an event or command by calling the proper functions or files.
		*@return mixed string ID of event on success, or False if failed.
		*/
	function register( $time, $data, $filename = FALSE, $code = FALSE ) {
		if ( $filename == FALSE && $code == FALSE) {
			intmsg( 'TIMER: Something tried to register a timed event without required infomation.' );
			return false;
		} elseif ( floor($time) < 1 ) {
			intmsg( 'TIMER: Something tried to register an invalid time: ' . $time );
			return false;
		} else {
			$time = microtime( TRUE ) + floor($time);
			$id = sha1( uniqid( "", TRUE ) );
			$this->events[] = array(
					'id'		=>	$id, 		//Event ID
					'time'		=>	$time, 		//Time of event
					'recurring'	=>	FALSE,
					'times'		=>	FALSE,
					'data'		=>	$data, 		//Data for the event to use.
					'file'		=>	$filename, 	//Include a file
					'code'		=>	$code, 		//Evaluate code upon firing. Dangerous.
			);
			//var_dump($this->events); //Debugging code ;)
			return $id;
		}
	}

	/**
		*Register an event that reoccurs
		*
		*For recurring events use Timer::registerRecurring()
		*
		*There are two ways of having things happen when a time event is fired.
		*
		*1. Include a file.
		*
		*2. Eval() custom code (Dangerous) Only used if $filename=false
		*
		*Timed events do not have access to  the normal range of data a module would. You need to store the data a timed event will use.
		*
		*$time the number of seconds in which to fire the event. Must be 1 or greater or it's not registered. Floor() is called on $time so don't use decimals. (If you fire it more than once you need
		*
		*$times is the number of times to fire the event, use 0 if you want it to happen an infinite number of times.
		*
		*$data is storage for infomation that a timed event needs. The timer class is not able to call commands normally, although you could create an event or command by calling the proper functions or files.
		*@return mixed string ID of event on success, or False if failed.
		*/
	function registerRecurring( $time, $data, $times, $filename = FALSE, $code = FALSE ) {
		$time = floor( $time );
		$times = floor( $times );
		if ( $times == 0 ) $times = FALSE;
		if ( $filename == FALSE && $code == FALSE) {
			intmsg( 'TIMER: Something tried to register a timed event without required infomation.' );
			return false;
		} elseif ($time < 1) {
			intmsg( "TIMER: Something tried to register an invalid time: $time" );
			return false;
		} elseif ($times < 0) {
			intmsg( "TIMER: Something tried to register an invalid number of times: $times" );
			return false;
		} else {
			$int = $time;
			$time = microtime( TRUE ) + $time;
			$id = sha1( uniqid( "", TRUE ) );
			$this->events[] = array(
					'id'		=>	$id,
					'time'		=>	$time, 		//Time of event
					'int'		=>	$int,
					'recurring'	=>	TRUE,		//Need to set these, if is not false, it stores the interval.
					'times'		=>	$times,
					'data'		=>	$data, 		//Data for the event to use.
					'file'		=>	$filename, 	//Include a file
					'code'		=>	$code, 		//Evaluate code upon firing. Dangerous.
			);
			//var_dump($this->events); //Debugging code ;)
			return $id;
		}
	}
	/**
		*Fire event
		*
		*This funcion checks for events to fire and fires them if the time is right.
		*@return integer Number of events fired.
		*/
	function fire() {
		//The weird variable names are used to reduce the chances of eval()ed or included code from blowing the workings out of this class.
		$firedEvents = 0;
		$time = microtime( TRUE );
		foreach( $this->events as $EVENTLOOPKEY => $EVENTLOOPINFO ) {
			if ( $EVENTLOOPINFO['time'] <= $time ) {
				//Set Data
				$file = $EVENTLOOPINFO['file'];
				$code = $EVENTLOOPINFO['code'];
				//Data for use by all events.
				$data = $EVENTLOOPINFO['data'];
				// Check
				if ( $file ) {
					if ( file_exists( f( $file ) ) ) {
						include f( $file );
						$firedEvents++;
					} else intmsg( "TIMER EVT: File not found: $file" );
				} elseif ( $code ) {
					if ( empty( $code ) ) intmsg( "TIMER EVT: Code to eval is empty... " );
					elseif (FALSE === eval( $code ) ) intmsg( "TIMER EVT: Parse error on eval'ed code" );
					else $firedEvents++;
				} else intmsg( "TIMER EVT: What the??? Event called but there is nothing to fire." );
				if ( $EVENTLOOPINFO['recurring'] == TRUE && $EVENTLOOPINFO['times'] === FALSE) {
					$this->events[$EVENTLOOPKEY]['time'] = microtime( TRUE ) + $this->events[$EVENTLOOPKEY]['int'];
				} elseif ( $EVENTLOOPINFO['times'] > 1 && $EVENTLOOPINFO['recurring'] == TRUE ) {
					$this->events[$EVENTLOOPKEY]['times'] = $this->events[$EVENTLOOPKEY]['times'] - 1;
					$this->events[$EVENTLOOPKEY]['time'] = microtime( TRUE ) + $this->events[$EVENTLOOPKEY]['int'];
				} else {
					unset ( $this->events[$EVENTLOOPKEY] );
					sort( $this->events ); //Clean up the array :D
				}
			}
		}
		return $firedEvents;
	}
	/**
		*Remove an event. Accepts the ID of the event.
		*@return bool
		*/
	function cancel( $id ) {
		foreach( $this->events as $i => $keys ) {
			if( $keys['id'] == $id ) {
				unset( $this->events[$i] );
				return true;
			}
		}
		return false;
	}
}
?>
