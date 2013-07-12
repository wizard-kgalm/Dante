<?php
//Fixed
class flood {

  //Massive data array
	public $users = array(); // Data to store...

	//Regular settings
	private $period;
	private $times;
	private $timeout;

	//Repeat info
	private $rPeriod;
	private $rTimes;
	private $rTimeout;
	private $on;

	/**
	 *Initialize class
	 *@return void
	 */
	function flood( $enable, $period, $times, $timeout, $repeating = FALSE, $rPeriod = 0, $rTimes = 0, $rTimeout = 0 ) {
		global $config;
		$this->users = array(
			'times'		=>	array(),
			'ignore'	=>	array(),
			'repeat'	=>	array(),
			'ban'		=>	array(),
		);
		// Is this class online?
		$this->on			= 	$enable;
		// Time frame to watch
		$this->period		=	$period;
		// Number of times something can happen in time frame
		$this->times		=	$times;
		// Time class will ignore that something after it exceeds the limits.
		$this->timeout		=	$timeout;
		// Allow for a second level of flood protection.
		// Repeating activates if the something is ignored rTimes in rPeriod length of time.
		$this->useRepeating	=	$repeating;
		// Time frame to watch
		$this->rPeriod		=	$rPeriod;
		// Number of times something can be ignored
		$this->rTimes		=	$rTimes;
		// Time class will ignore that something after it exceeds the limits.
		$this->rTimeout		=	$rTimeout;
	}

	/**
	 *Check for flooding
	 *@return bool false on not flooding, 1 if user is ignored, 2 if user has just been ignored., 3 if user has just been banned.
	 */
	function check( $from ) {
		if( !$this->on ) return FALSE;
		$from = strtolower( $from );
		if( $this->isIgnored( $from ) ) return 1;
		$return = FALSE;
		$i		= ( count( $this->users['times'][$from] ) );
		if ( !array_key_exists( $from, $this->users['times'] ) ) {
			$this->users['times'][$from] = array( 0 => time() );
		}
		$this->users['times'][$from][] = time();
		// Get array index to check
		$i = ( $i - $this->times );
		if( $i <= 0 ) return FALSE; // If the list isn't long enough, no flooding then.
		// Get index time compared to the period.
		$t = ( $this->users['times'][$from][$i] + $this->period );
		//Need to check for
		if( $t > time() ) {
			// User is flooding
			$this->ignore( $from, $this->timeout );
			$return = 2;
			if( $this->checkRepeat( $from ) ) $return = 3;
		} else {
			// User not flooding
			$return=  FALSE;
		}
		if( $i = ( $this->times + 5 ) ) unset( $this->users['times'][$from][0] );//clear out last one.
		sort( $this->users['times'][$from] );
		return $return;
	}

	/**
	 *Check for repeated flooding
	 *@return bool
	 */
	private function checkRepeat( $from ) {
		if( !$this->on || !$this->useRepeating ) return FALSE;
		if( $this->isBanned( $from ) ) return FALSE;
		$from	=	strtolower($from);
		$i		=	count( $this->users['repeat'][$from] );
		if ( !array_key_exists( $from, $this->users['repeat'] ) ) {
			$this->users['repeat'][$from] = array( 0 => time() );
		}
		$this->users['repeat'][$from][] = time();
		$i = ( $i - $this->times );
		if( $i <= 0 ) return FALSE; // If the list isn't long enough, no flooding then.
		$t = ( $this->users['repeat'][$from][$i] + $this->rPeriod );
		if( $t > time() ) {
			// User is flooding a lot, ban time.
			$this->ignore( $from, $this->rTimeout, TRUE);
			$return = TRUE;
		} else {
			// User not flooding.
			$return = FALSE;
		}
		if( $i = ( $this->times + 5 ) ) unset( $this->users['repeat'][$from][0] ); // Clear out last one.
		sort( $this->users['repeat'][$from] );
		return $return;
	}

	//User is annoying, ignore
	function ignore( $from, $timeout, $ban = FALSE ) {
		$from = strtolower( $from );
		$this->users['ignore'][$from] = time() + $timeout;
		if($ban) $this->users['ban'][$from] = time() + $timeout;
	}

	//Is user ignored?
	function isIgnored( $from ) {
		if( !$this->on ) return FALSE;
		$from = strtolower( $from );
		if( array_key_exists( $from, $this->users['ignore'] ) ) {
			if( $this->users['ignore'][$from] <= time() ) {
				// Unignore
				unset( $this->users['ignore'][$from] );
				return FALSE;
			} else {
				// Still ignored
				return TRUE;
			}

		} else return FALSE;
	}
	function isBanned( $from ) {
		if( !$this->on ) return FALSE;
		$from = strtolower( $from );
		if( array_key_exists( $from, $this->users['ban'] ) ) {
			if( $this->users['ban'][$from] <= time() ) {
				// Unban
				unset( $this->users['ban'][$from] );
				return FALSE;
			} else {
				//still banned
				return TRUE;
			}

		} else return false;
	}

	function setPeriod( $value = FALSE ) {
		if( $value === FALSE ) {
			return $this->period;
		} else {
			if( !is_numeric( $value ) ) return FALSE;
			$this->period = $value;
			return TRUE;
		}
	}

	function setTime( $value = FALSE ) {
		if( $value === FALSE ) {
			return $this->times;
		} else {
			if( !is_numeric( $value ) ) return FALSE;
			$this->times = $value;
			return TRUE;
		}
	}

	function setTimeout( $value = FALSE ) {
		if( $value === FALSE ) {
			return $this->timeout;
		} else {
			if( !is_numeric( $value ) ) return FALSE;
			$this->timeout = $value;
			return TRUE;
		}
	}

	function setRPeriod( $value = FALSE ) {
		if($value === FALSE) {
			return $this->rPeriod;
		} else {
			if( !is_numeric( $value ) ) return FALSE;
			$this->rPeriod = $value;
			return TRUE;
		}
	}

	function setRTime( $value = false ) {
		if($value === FALSE) {
			return $this->rTimes;
		} else {
			if( !is_numeric( $value ) ) return FALSE;
			$this->rTimes = $value;
			return TRUE;
		}
	}

	function setRTimeout( $value = FALSE ) {
		if( $value === FALSE ) {
			return $this->rTimeout;
		} else {
			if( !is_numeric( $value ) ) return FALSE;
			$this->rTimeout = $value;
			return TRUE;
		}
	}

	function setRepeating( $value = "returnplz" ) {

		if( $value == "returnplz" ) {
			return $this->useRepeating;
		} else {
			if( !is_bool( $value ) ) return FALSE;
			$this->useRepeating = $value;
			return TRUE;
		}
	}

	function on() {
		if( $this->on ) {
			return FALSE;
		} else {
			$this->on = TRUE;
			return TRUE;
		}
	}

	function off() {
		if(!$this->on) {
			return FALSE;
		} else {
			$this->on = FALSE;
			return TRUE;
		}
	}

}
