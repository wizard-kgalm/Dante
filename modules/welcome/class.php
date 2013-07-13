<?php
//Class for the welcome module.
class Welcome {

	// No return needed as this directly modifies the passed value.
	function parseRoom(&$c) {
		if( $c === FALSE ) $c = ':global:';
		elseif ( $c == ':global:' ) $c = $c;
		else $c = strtolower( rChatName( $c ) );
	}

	function parseUser(&$user) {
		$user = strtolower( $user );
	}

	//INDV STUFFS HERE =========================================
	//If $c is false, the function assumes global.
	//@return mixed
	function getIndv( $user, $c = FALSE ) {
		global $config;
		$this->parseRoom( $c );
		$this->parseUser( $user );
		if( isset( $config['welcome'][$c]['indv'][$user] ) ) {
			return $config['welcome'][$c]['indv'][$user];
		} else {
			return false;
		}
	}

	//@return void
	function setIndv( $user, $c, $msg ) {
		global $config;
		$this->parseRoom( $c );
		$this->parseUser( $user );
		$config['welcome'][$c]['indv'][$user] = $msg;
	}

	//@return bool
	function clearIndv( $user, $c = FALSE ) {
		global $config;
		$this->parseRoom( $c );
		$this->parseUser( $user );
		if( isset( $config['welcome'][$c]['indv'][$user] ) ) {
			$config['welcome'][$c]['indv'][$user] = '';
			return true;
		} else {
			return false;
		}
	}

	//PRIV STUFFS HERE =========================================
	//@return mixed
	function getPriv( $priv, $c = FALSE ) {
		global $config;
		$this->parseRoom( $c );
		$priv = strtolower( $priv );
		if( isset( $config['welcome'][$c]['pc'][$priv] ) ) {
			return $config['welcome'][$c]['pc'][$priv];
		} else {
			return false;
		}
	}

	//@return void
	function setPriv( $priv, $c, $msg ) {
		global $config;
		$this->parseRoom( $c );
		$priv = strtolower( $priv );
		$config['welcome'][$c]['pc'][$priv] = $msg;
	}

	//@return bool
	function clearPriv( $priv, $c = FALSE ) {
		global $config;
		$this->parseRoom( $c );
		$priv = strtolower( $priv );
		if( isset( $config['welcome'][$c]['pc'][$priv] ) ) {
			$config['welcome'][$c]['pc'][$priv] = '';
			return true;
		} else {
			return false;
		}
	}

	//ALL STUFFS HERE =========================================
	//@return mixed
	function getAll( $c = FALSE ) {
		global $config;
		$this->parseRoom( $c );
		if( isset( $config['welcome'][$c]['all'] ) ) {
			return $config['welcome'][$c]['all'];
		} else {
			return false;
		}
	}

	//@return void
	function setAll( $c, $msg ) {
		global $config;
		$this->parseRoom( $c );
		$config['welcome'][$c]['all'] = $msg;
	}

	//@return bool
	function clearAll( $c = FALSE ) {
		global $config;
		$this->parseRoom( $c );
		if( isset( $config['welcome'][$c]['all'] ) ) {
			$config['welcome'][$c]['all'] = '';
			return true;
		} else {
			return false;
		}
	}

	//ORDER STUFFS HERE =========================================
	function getOrder( $c=false ) {
		global $config;
		$this->parseRoom( $c );
		if( isset( $config['welcome'][$c]['order'] ) ) {
			return $config['welcome'][$c]['order'];
		} else {
			return false;
		}
	}

	//@return mixed, false on failure, array('all'=>#,'priv'=>#,'indv'=>#) on success
	function setOrder( $c, $order ){
		global $config;
		$this->parseRoom( $c );
		$ord=explode( ",", $order );
		//Check for proper set
		if ( ( count( $ord ) != 3 ) || ( !is_numeric( trim( $ord[0] ) ) ) || ( !is_numeric( trim( $ord[1] ) ) ) || ( !is_numeric( trim( $ord[2] ) ) ) ) return false;
		$orders = array(
			'all'	=>	trim( $ord[2] ),
			'pc'	=>	trim( $ord[1] ),
			'indv'	=>	trim( $ord[0] ),
		);

		if( is_array( $config['welcome'][$c]['order'] ) ) $config['welcome'][$c]['order'] = array_merge( $config['welcome'][$c]['order'], $orders );
		else $config['welcome'][$c]['order'] = $orders;

		return $config['welcome'][$c]['order'];
	}

	//SWITCH STUFFS HERE =========================================
	//@return mixed
	function getSwitch( $c = FALSE ) {
		global $config;
		$this->parseRoom( $c );
		if( isset( $config['welcome'][$c]['order']['isActive'] ) ) {
			return $config['welcome'][$c]['order']['isActive'];
		} else {
			return false;
		}
	}

	//@return void
	function setSwitchOn( $c = FALSE ) {
		global $config;
		$this->parseRoom( $c );
		$config['welcome'][$c]['order']['isActive'] = TRUE;
	}

	//@return void
	function setSwitchOff($c = FALSE) {
		global $config;
		$this->parseRoom( $c );
		$config['welcome'][$c]['order']['isActive'] = FALSE;
	}

	//COMBINE STUFFS HERE =========================================
	//@return mixed
	function getCombine( $c = FALSE) {
		global $config;
		$this->parseRoom( $c );
		if( isset( $config['welcome'][$c]['order']['combine'] ) ) {
			return $config['welcome'][$c]['order']['combine'];
		} else {
			return false;
		}
	}

	//@return void
	function setCombineOn( $c ){
		global $config;
		$this->parseRoom( $c );
		$config['welcome'][$c]['order']['combine'] = TRUE;
	}

	//@return void
	function setCombineOff( $c ) {
		global $config;
		$this->parseRoom( $c );
		$config['welcome'][$c]['order']['combine'] = FALSE;
	}
	//First STUFFS HERE =========================================
	/*
	 These functions get and set which things have proirity.
	 */
	//@return mixed
	function getWhichFirst( $c = FALSE ) {
		global $config;
		$this->parseRoom( $c );
		if( isset( $config['welcome'][$c]['order']['globalFirst'] ) ) {
			return $config['welcome'][$c]['order']['globalFirst'];
		} else {
			return false;
		}
	}

	//@return void
	function setGlobalsFirst( $c = FALSE) {
		global $config;
		$this->parseRoom( $c );
		$config['welcome'][$c]['order']['globalFirst'] = TRUE;
	}
	//@return void
	function setGlobalsLast( $c = FALSE) {
		global $config;
		$this->parseRoom( $c );
		$config['welcome'][$c]['order']['globalFirst'] = FALSE;
	}
	//END OF CLASS
}
//Init Class
$welcome = new Welcome;
?>