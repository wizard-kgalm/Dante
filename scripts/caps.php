<?php
// DANTE SCRIPT ======
// Name: CAPS
// Description: Toggle caps lock spam or convert a message to all caps. :3c
// Author: Wizard-Kgalm 
// Version: 1.0
// Priv: default
// Help:  "??caps on/off" OR "??caps (#room) message" The #room is optional. :3
// ====================
if( $args[1] == "on" ){
  $config->df['caps']['on'] = TRUE;
	$config->save_info( "./config/caps.df", $config->df['caps'] );
	$dAmn->say( "Cruise control engaged!", $c );
}elseif( $args[1] == "off" ){
	$config->df['caps']['on'] = FALSE;
	$config->save_info( "./config/caps.df", $config->df['caps'] );
	$dAmn->say( "Cruise control deactivated.", $c );
}elseif( strtolower( $args[1] ) !== "on" || strtolower( $args[1] ) !== "off"){
	if( $args[1][0] == "#" ){
		if( $args[2] == "/me" ){
			$dAmn->me(    strtoupper( $argsE[3] ), $args[1] );
		}else
		if( $args[2] == "/npmsg" ){
			$dAmn->npmsg( strtoupper( $argsE[3] ), $args[1] );
		}else
			$dAmn->say(   strtoupper( $argsE[2] ), $args[1] );
	}elseif($args[1] == "/me"){
		$dAmn->me(        strtoupper( $argsE[2] ), $c       );
	}elseif($args[1] == "/npmsg"){
		$dAmn->npmsg(     strtoupper( $argsE[2] ), $c       );
	}else
		$dAmn->say(       strtoupper( $argsE[1] ), $c       );
}
?>
