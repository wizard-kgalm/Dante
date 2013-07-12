<?php
// DANTE SCRIPT ======
// Name: esreveR
// Description: Toggle reverse on/off or convert a message to all reverse.
// Author: Wizard-Kgalm 
// Version: 1.1
// Priv: default
// Help: {tr}reverse on/off" OR "{tr}reverse (#room) message" The #room is optional.
// ====================
if( $args[1] == "on" ){
  $config->df['reverse']['on'] = TRUE;
	$config->save_info( "./config/reverse.df", $config->df['reverse'] );
	$dAmn->say(".detavitca esreveR",$c);
}elseif($args[1] == "off"){
	$config->df['reverse']['on'] =  FALSE;
	$config->save_info( "./config/reverse.df", $config->df['reverse'] );
	$dAmn->say("Reverse deactivated.",$c);
}elseif( strtolower( $args[1] ) !== "on" || strtolower( $args[1] ) !== "off" ){
	if( $args[1][0] == "#" ){
		if( $args[2] == "/me" ){
			$dAmn->me( "&#8238;".$argsE[3], $args[1] );
		}else
		if( $args[2] == "/npmsg" ){
			$dAmn->npmsg( "&#8238;".$argsE[3], $args[1] );
		}else
			$dAmn->say( "&#8238;".$argsE[2], $args[1] );
	}elseif( $args[1] == "/me" ){
		$dAmn->me( "&#8238;".$argsE[2] ,$args[1] );
	}else
	if( $args[1] == "/npmsg" ){
		$dAmn->npmsg( "&#8238;".$argsE[2], $args[1] );
	}else
		$dAmn->say( "&#8238;".$argsE[2], $args[1] );
}
