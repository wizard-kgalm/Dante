<?php

switch( $args[0] ) {
	case "debug":
		global $debugLevel;
		if ( is_numeric( $args[1] ) ) {
			if ( ( $args[1] < 6 ) && ( $args[1] > -1 ) ) {
				$debugLevel = $args[1];
				$dAmn->say( "Debug level now set to: $debugLevel", $c );
				if ( $args[1] < 3 ) error_reporting( E_ALL ^ E_NOTICE );
				else error_reporting( E_ALL );
			} else $dAmn->say( "Debug (0-5)", $c );
		} elseif ( empty( $args[1] ) ) $dAmn->say("Debug level is currently set to: $debugLevel", $c );
		else $dAmn->say( "Debug (0-5)", $c );
		break;
		//Fun little tools for developers. I hope to add more later
	case "execute":
		if ( !$user->has($from,0) ) {
			$dAmn->say( 'Sorry, only the actual owner can mess with this.', $c );
			break;
		}
		$evaluation = $argsF;
		$evaluation = html_entity_decode($argsF);
		$PROTECTEDCHATROOMNAME = $c;
		if ( false === eval( $evaluation ) ) {
			$dAmn->say( 'Parse error, check the console.', $PROTECTEDCHATROOMNAME );
		}
		break;
	case "reconfig":
		load_config();
		$dAmn->say( "Config has been reloaded", $c );
		break;
	case "saveconfig":
		if ( $args[1] == 'force' ) {
			save_config(false,true);
		} else {
			save_config();
		}
		$dAmn->say( "Config saved", $c );
		break;

	case "vardump":
		if ( '$' == substr( $args[1], 0, 1 ) ) {
			$evaluation = 'global ' . $args[1] . '; var_dump(' . $args[1] . ');';
		} else {
			$evaluation = 'global $' . $args[1] . '; var_dump($' . $args[1] . ');';
		}
		eval( $evaluation );
		break;
}
?>