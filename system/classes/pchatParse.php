<?php

class pchatParse{
  static $ids = array();
	static $data = '';
	static $room = '';
	static $id = '';
	static $from = '';
	
	static function isData( $msg, $c ) {
		$args = explode( " ", $msg, 5 );
		if( $c == "@www" && $args[0] == "DATAPARSE" && array_key_exists( $args[1], self::$ids ) ) {
			self::$from = $args[3];
			self::$room = $args[2];
			self::$data = $args[4];
			self::$id = $args[1];
			self::del( self::$id );
			return TRUE;
		} else return FALSE;
	}
	
	static function send( $data, $from, $c ) {
		global $config;
		do {
			$l = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz#%.-_,;:"; 
			$code = ""; 
			for( $i = 1; $i <= mt_rand(35,60); $i++ ) { 
				$code .= $l[rand(0, strlen($l))];
			}
			$id = $code;
			$x = array_key_exists( $id, self::$ids );
		} while( $x == TRUE );
		self::$ids[$id] = time();
		$dAmn->say( "DATAPARSE {$id} {$c} {$from} {$data}", $c );
	}
		
	static function del( $id ) {
		if( array_has_key( $id, self::$ids ) ) {
			unset( self::$ids[$id] );
			return true;
		} else {
			return false;
		}
	}
}
