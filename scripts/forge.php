<?php
// DANTE SCRIPT ======
// Name: Forge
// Description: Forge a "quoted" message.
// Author: Wizard-Kgalm
// Version: 0.1
// Priv: 0
// Help: forge [action/username] [message]. If action, forge [action] [username] [message]. 
// ====================
if( empty( $args[1] ) ){
  return $dAmn->say( "{$from}: You must provide someone to forge messages from.", $c );
}
$dateobj = date_create( "now", timezone_open( date_default_timezone_get() ) );
if( $args[1] == "action" ){
	if( $args[2][0] == "#" ){
		$todochat = $args[2];
		return $dAmn->say( date_format( $dateobj, "g:i:sA" )." <i><b>* {$args[3]}</b> $argsE[4] </i>", $todochat );
	}else{
		$todochat = $c;
	}
	return $dAmn->say( date_format( $dateobj, "g:i:sA" )." <i><b>* {$args[2]}</b> $argsE[3] </i>", $todochat );
}
if( $args[1][0] =='#' ){
	$todochat = $args[1];
	return $dAmn->say( date_format( $dateobj, "g:i:sA" )." <b><{$args[2]}></b> $argsE[3]", $todochat );
}
$todochat = $c;
$dAmn->say( date_format( $dateobj, "g:i:sA" )." <b><{$args[1]}></b> $argsE[2]", $todochat );
unset( $todochat );
return;
